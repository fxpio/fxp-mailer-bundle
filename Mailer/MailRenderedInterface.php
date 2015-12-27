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

use Sonatra\Bundle\MailerBundle\Model\MailInterface;

/**
 * Interface for the mail rendered.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
interface MailRenderedInterface
{
    /**
     * Get the mail template.
     *
     * @return MailInterface
     */
    public function getTemplate();

    /**
     * Get the rendered subject.
     *
     * @return string|null
     */
    public function getSubject();

    /**
     * Get the rendered HTML body.
     *
     * @return string|null
     */
    public function getHtmlBody();

    /**
     * Get the rendered body.
     *
     * @return string|null
     */
    public function getBody();
}
