<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Event;

use Sonatra\Bundle\MailerBundle\Mailer\MailRenderedBuilderInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class event for the template.post_render event.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class FilterPostRenderEvent extends Event
{
    /**
     * @var MailRenderedBuilderInterface
     */
    protected $mailRenderedBuilder;

    /**
     * Constructor.
     *
     * @param MailRenderedBuilderInterface $mailRenderedBuilder The mail rendered builder
     */
    public function __construct(MailRenderedBuilderInterface $mailRenderedBuilder)
    {
        $this->mailRenderedBuilder = $mailRenderedBuilder;
    }

    /**
     * Get the mail rendered builder.
     *
     * @return MailRenderedBuilderInterface
     */
    public function getMailRenderedBuilder()
    {
        return $this->mailRenderedBuilder;
    }
}
