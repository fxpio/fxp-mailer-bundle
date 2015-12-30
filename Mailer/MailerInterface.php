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

use Sonatra\Bundle\MailerBundle\Exception\InvalidArgumentException;
use Sonatra\Bundle\MailerBundle\MailTypes;
use Sonatra\Bundle\MailerBundle\Transport\TransportInterface;

/**
 * Interface for the mailer.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
interface MailerInterface
{
    /**
     * Add a mail transport.
     *
     * @param TransportInterface $transport The mail transport
     *
     * @return self
     */
    public function addTransport(TransportInterface $transport);

    /**
     * Check if the mail transport is present.
     *
     * @param string $name The name of the mail transport
     *
     * @return bool
     */
    public function hasTransport($name);

    /**
     * Get the mail transport.
     *
     * @param string $name The name of the mail transport
     *
     * @return TransportInterface
     *
     * @throws InvalidArgumentException When the transport does not exist
     */
    public function getTransport($name);

    /**
     * Send the message with a specific transport.
     *
     * @param string      $transport The name of transport
     * @param mixed       $message   The message for the specific transport
     * @param string|null $template  The mail template name
     * @param array       $variables The variables of template
     * @param string      $type      The mail type defined in MailTypes::TYPE_*
     *
     * @return bool
     */
    public function send($transport, $message, $template = null, array $variables = array(), $type = MailTypes::TYPE_ALL);
}
