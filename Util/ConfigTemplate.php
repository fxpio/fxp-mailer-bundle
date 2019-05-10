<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Bundle\MailerBundle\Util;

use Fxp\Component\Mailer\Model\Layout;
use Fxp\Component\Mailer\Model\LayoutTranslation;
use Fxp\Component\Mailer\Model\Mail;
use Fxp\Component\Mailer\Model\MailTranslation;

/**
 * Config of template classes.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
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
    public function __construct(
        string $layoutClass = Layout::class,
        string $mailClass = Mail::class,
        string $layoutTranslationClass = LayoutTranslation::class,
        string $mailTranslationClass = MailTranslation::class
    ) {
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
    public function getLayoutClass(): string
    {
        return $this->layoutClass;
    }

    /**
     * Get the class name of mail.
     *
     * @return string
     */
    public function getMailClass(): string
    {
        return $this->mailClass;
    }

    /**
     * Get the class name of layout translation.
     *
     * @return string
     */
    public function getLayoutTranslationClass(): string
    {
        return $this->layoutTranslationClass;
    }

    /**
     * Get the class name of mail translation.
     *
     * @return string
     */
    public function getMailTranslationClass(): string
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
    public function getTemplateClass(string $type): string
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
    public function getTemplateTranslationClass(string $type): string
    {
        return 'layout' === $type ? $this->getLayoutTranslationClass() : $this->getMailTranslationClass();
    }
}
