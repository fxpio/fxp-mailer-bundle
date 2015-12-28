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

/**
 * Utils for translation.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
abstract class TranslationUtil
{
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
     * @param object $template    The template instance
     * @param object $translation The template translation instance
     * @param string $field       The field
     */
    protected static function injectValue($template, $translation, $field)
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
}
