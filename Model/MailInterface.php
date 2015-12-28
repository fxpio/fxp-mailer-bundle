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
interface MailInterface extends TemplateInterface
{
    /**
     * Set the mail type.
     *
     * @param string $type The mail type
     *
     * @return self
     */
    public function setType($type);

    /**
     * Get the mail type.
     *
     * @return string
     */
    public function getType();

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

    /**
     * Set the layout.
     *
     * @param LayoutInterface $layout The layout
     *
     * @return self
     */
    public function setLayout(LayoutInterface $layout);

    /**
     * Get the layout.
     *
     * @return LayoutInterface|null
     */
    public function getLayout();

    /**
     * Get the mail translations.
     *
     * @return MailTranslationInterface[]|\Doctrine\Common\Collections\Collection
     */
    public function getTranslations();

    /**
     * Add a mail translation.
     *
     * @param MailTranslationInterface $translation The mail translation
     *
     * @return self
     */
    public function addTranslation(MailTranslationInterface $translation);

    /**
     * Remove a mail translation.
     *
     * @param MailTranslationInterface $translation The mail translation
     *
     * @return self
     */
    public function removeTranslation(MailTranslationInterface $translation);

    /**
     * Get the translated mail.
     *
     * @param string $locale The locale
     *
     * @return self
     */
    public function getTranslation($locale);
}
