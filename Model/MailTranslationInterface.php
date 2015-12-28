<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Model;

/**
 * Interface for the mail template.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
interface MailTranslationInterface extends TemplateTranslationInterface
{
    /**
     * Get the reference mail.
     *
     * @return MailInterface
     */
    public function getMail();

    /**
     * Set the subject.
     *
     * @param string|null $subject The subject
     *
     * @return self
     */
    public function setSubject($subject);

    /**
     * Get the subject.
     *
     * @return string|null
     */
    public function getSubject();

    /**
     * Set the html body.
     *
     * @param string|null $htmlBody The html body
     *
     * @return self
     */
    public function setHtmlBody($htmlBody);

    /**
     * Get the html body.
     *
     * @return string|null
     */
    public function getHtmlBody();
}
