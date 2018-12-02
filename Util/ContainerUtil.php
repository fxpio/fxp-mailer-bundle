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

use Fxp\Component\Mailer\MailTypes;
use Fxp\Component\Mailer\Model\TemplateFileInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Utils for container builder.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
abstract class ContainerUtil
{
    /**
     * Add the templates.
     *
     * @param ContainerBuilder    $container The container
     * @param string              $type      The template type
     * @param array               $templates The template configs of layouts
     * @param ConfigTemplate|null $config    The template configurator
     */
    public static function addTemplates(ContainerBuilder $container, $type, array $templates, ConfigTemplate $config = null)
    {
        $config = null === $config ? new ConfigTemplate() : $config;

        foreach ($templates as $template) {
            $def = new Definition($config->getTemplateClass($type));
            $def->addTag(sprintf('fxp_mailer.%s', $type));
            static::addTemplateValues($def, $type, $template);

            $defId = sprintf('fxp_mailer.%s.%s', $type, str_replace('-', '_', $template['name']));

            if (isset($template['translations'])) {
                static::addTemplateTranslations($container, $def, $defId, $type, $template['translations'], $template['name'], $config);
            }

            $container->setDefinition($defId, $def);
        }
    }

    /**
     * Add the template translations.
     *
     * @param ContainerBuilder    $container    The container
     * @param Definition          $def          The definition of template
     * @param string              $defId        The service id of template definition
     * @param string              $type         The template type
     * @param array               $translations The translations of template
     * @param string              $name         The name of template
     * @param ConfigTemplate|null $config       The template configurator
     */
    public static function addTemplateTranslations(ContainerBuilder $container, Definition $def, $defId, $type, array $translations, $name, ConfigTemplate $config = null)
    {
        $config = null === $config ? new ConfigTemplate() : $config;

        foreach ($translations as $translation) {
            $transDef = new Definition($config->getTemplateTranslationClass($type));
            $transDef->addArgument(new Reference($defId));
            static::addArgumentValue($transDef, 'setLocale', $translation, 'locale');
            static::addArgumentValue($transDef, 'setLabel', $translation, 'label');
            static::addArgumentValue($transDef, 'setDescription', $translation, 'description');
            static::addArgumentValue($transDef, 'setBody', $translation, 'body');
            static::addArgumentFileValue($transDef, $translation);

            if ('mail' === $type) {
                static::addArgumentValue($transDef, 'setSubject', $translation, 'subject');
                static::addArgumentValue($transDef, 'setHtmlBody', $translation, 'html_body');
            }

            $transId = sprintf('fxp_mailer.%s_translation.%s', $type, str_replace('-', '_', $name));
            $container->setDefinition($transId, $transDef);
            $def->addMethodCall('addTranslation', [new Reference($transId)]);
        }
    }

    /**
     * Add the value in method argument if the value is different of the default value.
     *
     * @param Definition $def     The definition
     * @param string     $method  The name of method
     * @param array      $config  The template config
     * @param string     $field   The field name
     * @param mixed|null $default The default value
     */
    public static function addArgumentValue(Definition $def, $method, array $config, $field, $default = null)
    {
        $value = array_key_exists($field, $config) ? $config[$field] : $default;

        if ($default !== $value) {
            $def->addMethodCall($method, [$value]);
        }
    }

    /**
     * Add the file value in method argument if the service is an implementation of TemplateFileInterface.
     *
     * @param Definition $def    The definition
     * @param array      $config The template config
     */
    public static function addArgumentFileValue(Definition $def, array $config)
    {
        if (\in_array(TemplateFileInterface::class, class_implements($def->getClass()))) {
            static::addArgumentValue($def, 'setFile', $config, 'file');
        }
    }

    /**
     * Get the real filename.
     *
     * @param ContainerBuilder $container The container
     * @param string           $file      The filename
     *
     * @return string
     */
    public static function getRealFile(ContainerBuilder $container, $file)
    {
        $file = $container->getParameterBag()->resolveValue($file);

        if (0 === strpos($file, '@')) {
            $bundles = $container->getParameter('kernel.bundles');
            $bundle = substr($file, 1, strpos($file, 'Bundle') + 5);

            foreach ($bundles as $bundleName => $bundleClass) {
                if ($bundle === $bundleName) {
                    $refClass = new \ReflectionClass($bundleClass);
                    $bundleDir = \dirname($refClass->getFileName());
                    $file = $bundleDir.substr($file, \strlen($bundle) + 1);
                    break;
                }
            }
        }

        return $file;
    }

    /**
     * Add template values.
     *
     * @param Definition $def      The template definition
     * @param string     $type     The template type
     * @param array      $template The template config
     */
    protected static function addTemplateValues(Definition $def, $type, array $template)
    {
        static::addArgumentValue($def, 'setName', $template, 'name');
        static::addArgumentValue($def, 'setLabel', $template, 'label');
        static::addArgumentValue($def, 'setDescription', $template, 'description');
        static::addArgumentValue($def, 'setBody', $template, 'body');
        static::addArgumentValue($def, 'setEnabled', $template, 'enabled', true);
        static::addArgumentValue($def, 'setTranslationDomain', $template, 'translation_domain');
        static::addArgumentFileValue($def, $template);

        if ('mail' === $type) {
            static::addArgumentValue($def, 'setType', $template, 'type', MailTypes::TYPE_ALL);
            static::addArgumentValue($def, 'setSubject', $template, 'subject');
            static::addArgumentValue($def, 'setHtmlBody', $template, 'html_body');

            if (isset($template['layout']) && null !== $template['layout']) {
                $def->addMethodCall('setLayout', [new Reference('fxp_mailer.layout.'.str_replace('-', '_', $template['layout']))]);
            }
        }
    }
}
