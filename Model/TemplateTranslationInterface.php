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
interface TemplateTranslationInterface
{
    /**
     * Set the locale.
     *
     * @param string $locale The locale
     *
     * @return self
     */
    public function setLocale($locale);

    /**
     * Get the locale.
     *
     * @return string
     */
    public function getLocale();

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
}
