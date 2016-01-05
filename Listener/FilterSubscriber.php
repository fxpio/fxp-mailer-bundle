<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Listener;

use Sonatra\Bundle\MailerBundle\Event\FilterPostRenderEvent;
use Sonatra\Bundle\MailerBundle\Event\FilterPreSendEvent;
use Sonatra\Bundle\MailerBundle\Filter\FilterRegistryInterface;
use Sonatra\Bundle\MailerBundle\MailerEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * The filter listener of template and transport.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class FilterSubscriber implements EventSubscriberInterface
{
    /**
     * @var FilterRegistryInterface
     */
    protected $registry;

    /**
     * Constructor.
     *
     * @param FilterRegistryInterface $registry The filters registry
     */
    public function __construct(FilterRegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            MailerEvents::TEMPLATE_POST_RENDER => array(
                'onPostRender', 0,
            ),
            MailerEvents::TRANSPORT_PRE_SEND => array(
                'onPreSend', 0,
            ),
        );
    }

    /**
     * Action on post render event of mail templater.
     *
     * @param FilterPostRenderEvent $event The event
     */
    public function onPostRender(FilterPostRenderEvent $event)
    {
        foreach ($this->registry->getTemplateFilters() as $filter) {
            if (null !== ($mailRendered = $event->getMailRendered())
                    && $filter->supports($mailRendered)) {
                $filter->filter($mailRendered);
            }
        }
    }

    /**
     * Action on pre send event of mailer transport.
     *
     * @param FilterPreSendEvent $event The event
     */
    public function onPreSend(FilterPreSendEvent $event)
    {
        foreach ($this->registry->getTransportFilters() as $filter) {
            if ($filter->supports($event->getTransport(), $event->getMessage(), $event->getMailRendered())) {
                $filter->filter($event->getTransport(), $event->getMessage(), $event->getMailRendered());
            }
        }
    }
}
