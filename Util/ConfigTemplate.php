<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Util;

use Sonatra\Component\Mailer\Model\Layout;
use Sonatra\Component\Mailer\Model\LayoutTranslation;
use Sonatra\Component\Mailer\Model\Mail;
use Sonatra\Component\Mailer\Model\MailTranslation;

/**
 * Config of template classes.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ConfigTemplate
{
    protected $layoutClass;

    protected $mailClass;

    protected $layoutTranslationClass;

    protected $mailTranslationClass;

    /**
     * Constructor.
     *
     * @param string $layoutClass            The class name of layout
     * @param string $mailClass              The class name of mail
     * @param string $layoutTranslationClass The class name of layout translation
     * @param string $mailTranslationClass   The class name of mail translation
     */
    public function __construct($layoutClass = Layout::class, $mailClass = Mail::class,
                                $layoutTranslationClass = LayoutTranslation::class,
                                $mailTranslationClass = MailTranslation::class)
    {
        $this->layoutClass = $layoutClass;
        $this->mailClass = $mailClass;
        $this->layoutTranslationClass = $layoutTranslationClass;
        $this->mailTranslationClass = $mailTranslationClass;
    }

    /**
     * Get the class name of layout.
     *
     * @return string
     */
    public function getLayoutClass()
    {
        return $this->layoutClass;
    }

    /**
     * Get the class name of mail.
     *
     * @return string
     */
    public function getMailClass()
    {
        return $this->mailClass;
    }

    /**
     * Get the class name of layout translation.
     *
     * @return string
     */
    public function getLayoutTranslationClass()
    {
        return $this->layoutTranslationClass;
    }

    /**
     * Get the class name of mail translation.
     *
     * @return string
     */
    public function getMailTranslationClass()
    {
        return $this->mailTranslationClass;
    }

    /**
     * Get the class name of template.
     *
     * @param string $type The template type
     *
     * @return string
     */
    public function getTemplateClass($type)
    {
        return 'layout' === $type ? $this->getLayoutClass() : $this->getMailClass();
    }

    /**
     * Get the class name of template translation.
     *
     * @param string $type The template type
     *
     * @return string
     */
    public function getTemplateTranslationClass($type)
    {
        return 'layout' === $type ? $this->getLayoutTranslationClass() : $this->getMailTranslationClass();
    }
}
