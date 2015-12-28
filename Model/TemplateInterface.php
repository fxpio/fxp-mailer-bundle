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
 * Base interface for the template.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
interface TemplateInterface
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
}
