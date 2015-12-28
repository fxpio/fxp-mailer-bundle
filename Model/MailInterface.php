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

use Doctrine\Common\Collections\Collection;

/**
 * Interface for the mail template.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
interface MailInterface
{
    /**
     * Sets the unique template name.
     *
     * @param string $name The name
     *
     * @return self
     */
    public function setName($name);

    /**
     * Gets the unique template name.
     *
     * @return string
     */
    public function getName();

    /**
     * Set the label.
     *
     * @param string|null $label The label
     *
     * @return self
     */
    public function setLabel($label);

    /**
     * Get the label.
     *
     * @return string|null
     */
    public function getLabel();

    /**
     * Set the description.
     *
     * @param string|null $description The description
     *
     * @return self
     */
    public function setDescription($description);

    /**
     * Get the description.
     *
     * @return string|null
     */
    public function getDescription();

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
     * Set if the model is enabled.
     *
     * @param bool $enabled The enabled value
     *
     * @return self
     */
    public function setEnabled($enabled);

    /**
     * Check if the model is enabled.
     *
     * @return bool
     */
    public function isEnabled();

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
     * Set the body.
     *
     * @param string|null $body The body
     *
     * @return self
     */
    public function setBody($body);

    /**
     * Get the body.
     *
     * @return string|null
     */
    public function getBody();

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
     * Set the translation domain to use the translator.
     *
     * @param string|null $domain The translation domain
     *
     * @return self
     */
    public function setTranslationDomain($domain);

    /**
     * Get the translation domain to use the translator.
     *
     * @return string|null
     */
    public function getTranslationDomain();

    /**
     * Get the mail translations.
     *
     * @return MailTranslationInterface[]|Collection
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
