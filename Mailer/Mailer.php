<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Mailer;

use Sonatra\Bundle\MailerBundle\Event\FilterPostSendEvent;
use Sonatra\Bundle\MailerBundle\Event\FilterPreSendEvent;
use Sonatra\Bundle\MailerBundle\Exception\InvalidArgumentException;
use Sonatra\Bundle\MailerBundle\MailerEvents;
use Sonatra\Bundle\MailerBundle\MailTypes;
use Sonatra\Bundle\MailerBundle\Transport\TransportInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * The mailer.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class Mailer implements MailerInterface
{
    /**
     * @var MailTemplaterInterface
     */
    protected $templater;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var TransportInterface[]
     */
    protected $transports;

    /**
     * @param MailTemplaterInterface   $templater  The mail templater
     * @param TransportInterface[]     $transports The transports
     * @param EventDispatcherInterface $dispatcher The event dispatcher
     */
    public function __construct(MailTemplaterInterface $templater, array $transports,
                                EventDispatcherInterface $dispatcher)
    {
        $this->templater = $templater;
        $this->dispatcher = $dispatcher;

        foreach ($transports as $transport) {
            $this->addTransport($transport);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addTransport(TransportInterface $transport)
    {
        $this->transports[$transport->getName()] = $transport;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasTransport($name)
    {
        return isset($this->transports[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getTransport($name)
    {
        if (!isset($this->transports[$name])) {
            $msg = sprintf('The "%s" transport does not exist', $name);
            throw new InvalidArgumentException($msg);
        }

        return $this->transports[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function send($transport, $message, $template = null, array $variables = array(),
                         $type = MailTypes::TYPE_ALL)
    {
        $transportName = $transport;
        $transport = $this->getTransport($transport);
        $mailRendered = null !== $template
            ? $this->templater->render($template, $variables, $type)
            : null;

        $preEvent = new FilterPreSendEvent($transportName, $message, $mailRendered);
        $this->dispatcher->dispatch(MailerEvents::TRANSPORT_PRE_SEND, $preEvent);

        $res = $transport->send($preEvent->getMessage(), $preEvent->getMailRendered());

        $postEvent = new FilterPostSendEvent($res, $transportName, $message, $mailRendered);
        $this->dispatcher->dispatch(MailerEvents::TRANSPORT_POST_SEND, $postEvent);

        return $res;
    }
}
