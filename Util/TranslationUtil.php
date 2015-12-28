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

use Sonatra\Bundle\MailerBundle\Model\LayoutInterface;
use Sonatra\Bundle\MailerBundle\Model\MailInterface;
use Sonatra\Bundle\MailerBundle\Model\TemplateInterface;
use Sonatra\Bundle\MailerBundle\Model\TemplateTranslationInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Utils for translation.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
abstract class TranslationUtil
{
    /**
     * Translate the template with the translator.
     *
     * @param LayoutInterface|MailInterface $template   The template
     * @param string                        $locale     The locale
     * @param TranslatorInterface|null      $translator The translator
     *
     * @return LayoutInterface|MailInterface
     */
    public static function translate($template, $locale, TranslatorInterface $translator = null)
    {
        if (null === $template->getTranslationDomain()) {
            $template = $template->getTranslation($locale);
        } elseif (null !== $translator) {
            static::injectTranslatorValues($translator, $template);
        }

        return $template;
    }

    /**
     * Find the translation and translate the template if translation is found.
     *
     * @param LayoutInterface|MailInterface $template The template
     * @param string                        $locale   The locale
     *
     * @return bool
     */
    public static function find($template, $locale)
    {
        foreach ($template->getTranslations() as $translation) {
            if ($locale === $translation->getLocale()) {
                static::injectValue($template, $translation, 'label');
                static::injectValue($template, $translation, 'description');
                static::injectValue($template, $translation, 'body');

                if ($template instanceof MailInterface) {
                    static::injectValue($template, $translation, 'subject');
                    static::injectValue($template, $translation, 'htmlBody');
                }

                return true;
            }
        }

        return false;
    }

    /**
     * Inject the translation value in template.
     *
     * @param TemplateInterface            $template    The template instance
     * @param TemplateTranslationInterface $translation The template translation instance
     * @param string                       $field       The field
     */
    protected static function injectValue(TemplateInterface $template, TemplateTranslationInterface $translation, $field)
    {
        $setter = 'set'.ucfirst($field);
        $getter = 'get'.ucfirst($field);
        $refTpl = new \ReflectionClass($template);
        $refTrans = new \ReflectionClass($translation);

        if ($refTrans->hasMethod($getter) && $refTpl->hasMethod($setter)) {
            $val = $translation->{$getter}();

            if (null !== $val) {
                $template->{$setter}($val);
            }
        }
    }

    /**
     * Inject the translation values of translator in template.
     *
     * @param TranslatorInterface $translator The translator
     * @param TemplateInterface   $template   The template instance
     */
    protected static function injectTranslatorValues(TranslatorInterface $translator, TemplateInterface $template)
    {
        static::injectTranslatorValue($translator, $template, 'label');
        static::injectTranslatorValue($translator, $template, 'description');
        static::injectTranslatorValue($translator, $template, 'body');

        if ($template instanceof MailInterface) {
            static::injectTranslatorValue($translator, $template, 'subject');
            static::injectTranslatorValue($translator, $template, 'htmlBody');
        }
    }

    /**
     * Inject the translation value of translator in template.
     *
     * @param TranslatorInterface $translator The translator
     * @param TemplateInterface   $template   The template instance
     * @param string              $field      The field
     */
    protected static function injectTranslatorValue(TranslatorInterface $translator, TemplateInterface $template, $field)
    {
        $setter = 'set'.ucfirst($field);
        $getter = 'get'.ucfirst($field);
        $refTpl = new \ReflectionClass($template);

        if ($refTpl->hasMethod($getter) && $refTpl->hasMethod($setter)) {
            $val = $template->{$getter}();

            if (null !== $val) {
                $template->{$setter}($translator->trans($val, array(), $template->getTranslationDomain()));
            }
        }
    }
}
