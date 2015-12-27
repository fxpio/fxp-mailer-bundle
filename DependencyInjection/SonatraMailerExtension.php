<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\DependencyInjection;

use Sonatra\Bundle\MailerBundle\Loader\ConfigLayoutLoader;
use Sonatra\Bundle\MailerBundle\Loader\ConfigMailLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class SonatraMailerExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('templater.xml');

        $this->addLayoutTemplates($container, $config['layout_templates']);
        $this->addMailTemplates($container, $config['mail_templates']);
    }

    /**
     * Add the layout templates.
     *
     * @param ContainerBuilder $container The container
     * @param array            $templates The template configs of layouts
     */
    protected function addLayoutTemplates(ContainerBuilder $container, array $templates)
    {
        $def = new Definition(ConfigLayoutLoader::class);
        $def->setArguments(array($templates));
        $def->addTag('sonatra_mailer.layout_loader');

        $container->setDefinition('sonatra_mailer.loader.config_layout', $def);
    }

    /**
     * Add the mail templates.
     *
     * @param ContainerBuilder $container The container
     * @param array            $templates The template configs of mails
     */
    protected function addMailTemplates(ContainerBuilder $container, array $templates)
    {
        $def = new Definition(ConfigMailLoader::class);
        $def->setArguments(array($templates));
        $def->addTag('sonatra_mailer.mail_loader');

        $container->setDefinition('sonatra_mailer.loader.config_mail', $def);
    }
}
