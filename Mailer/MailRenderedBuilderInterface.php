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

/**
 * Interface for the mail rendered builder.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
interface MailRenderedBuilderInterface extends MailRenderedInterface
{
    /**
     * Set the subject.
     *
     * @param string|null $subject The subject
     *
     * @return self
     */
    public function setSubject($subject);

    /**
     * Set the html body.
     *
     * @param string|null $htmlBody The html body
     *
     * @return self
     */
    public function setHtmlBody($htmlBody);

    /**
     * Set the body.
     *
     * @param string|null $body The body
     *
     * @return self
     */
    public function setBody($body);

    /**
     * Build the mail rendered.
     *
     * @return MailRenderedInterface
     */
    public function build();
}
