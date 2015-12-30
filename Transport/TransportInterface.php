<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Transport;

use Sonatra\Bundle\MailerBundle\Exception\UnexpectedTypeException;
use Sonatra\Bundle\MailerBundle\Mailer\MailRenderedInterface;

/**
 * Interface for the transport.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
interface TransportInterface
{
    /**
     * Get the name.
     *
     * @return string
     */
    public function getName();

    /**
     * Send a mail.
     *
     * Call the TransportInterface::validate() method.
     *
     * @param mixed                      $message      The message for the specific transport
     * @param MailRenderedInterface|null $mailRendered The rendered mail
     *
     * @return bool
     *
     * @throws UnexpectedTypeException When the instance of message isn't valid for this transport
     */
    public function send($message, MailRenderedInterface $mailRendered = null);

    /**
     * Validate the message.
     *
     * @param mixed $message The message for the specific transport
     *
     * @throws UnexpectedTypeException When the instance of message isn't valid for this transport
     */
    public function validate($message);
}
