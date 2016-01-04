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

use Sonatra\Bundle\MailerBundle\Mailer\MailRenderedInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class event for the template.post_render event.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class FilterPostRenderEvent extends Event
{
    /**
     * @var MailRenderedInterface
     */
    protected $mailRendered;

    /**
     * Constructor.
     *
     * @param MailRenderedInterface $mailRendered The mail rendered
     */
    public function __construct(MailRenderedInterface $mailRendered)
    {
        $this->mailRendered = $mailRendered;
    }

    /**
     * Get the mail rendered.
     *
     * @return MailRenderedInterface
     */
    public function getMailRendered()
    {
        return $this->mailRendered;
    }
}
