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
 * Base class event for the transport.* event.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
abstract class AbstractFilterSendEvent extends Event
{
    /**
     * @var string
     */
    protected $transport;

    /**
     * @var mixed
     */
    protected $message;

    /**
     * @var MailRenderedInterface|null
     */
    protected $mailRendered;

    /**
     * Constructor.
     *
     * @param string                     $transport    The name of transport
     * @param mixed                      $message      The message for the specific transport
     * @param MailRenderedInterface|null $mailRendered The mail rendered
     */
    public function __construct($transport, $message, MailRenderedInterface $mailRendered = null)
    {
        $this->transport = $transport;
        $this->message = $message;
        $this->mailRendered = $mailRendered;
    }

    /**
     * Get the name of transport.
     *
     * @return string
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * Get the message for the specific transport.
     *
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get the mail rendered.
     *
     * @return MailRenderedInterface|null
     */
    public function getMailRendered()
    {
        return $this->mailRendered;
    }
}
