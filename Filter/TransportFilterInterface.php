<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Filter;

use Sonatra\Bundle\MailerBundle\Mailer\MailRenderedInterface;

/**
 * Interface for the transport filter.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
interface TransportFilterInterface
{
    /**
     * Filter the mail rendered.
     *
     * @param string                     $transport    The name of transport
     * @param mixed                      $message      The message for the specific transport
     * @param MailRenderedInterface|null $mailRendered The mail rendered
     */
    public function filter($transport, $message, MailRenderedInterface $mailRendered = null);

    /**
     * Check if the filter is compatible with the mail rendered.
     *
     * @param string                     $transport    The name of transport
     * @param mixed                      $message      The message for the specific transport
     * @param MailRenderedInterface|null $mailRendered The mail rendered
     *
     * @return bool
     */
    public function supports($transport, $message, MailRenderedInterface $mailRendered = null);
}
