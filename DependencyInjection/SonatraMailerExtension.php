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
use Symfony\Component\DependencyInjection\Reference;
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

        // model classes
        $container->setParameter('sonatra_mailer.layout_class', $config['layout_class']);
        $container->setParameter('sonatra_mailer.mail_class', $config['mail_class']);

        $loader->load('mailer.xml');
        $loader->load('templater.xml');
        $loader->load('doctrine_loader.xml');
        $loader->load('twig.xml');

        $container->setParameter('sonatra_mailer.transport_signers', $config['transport_signers']['signers']);

        if (class_exists('Swift_Message')) {
            $loader->load('transport_swiftmailer.xml');
            $this->addSwiftMailerDkimSigner($container, $config['transport_signers']['swiftmailer_dkim']);
        }

        $this->addTemplates($container, 'layout', ConfigLayoutLoader::class, $config['layout_templates']);
        $this->addTemplates($container, 'mail', ConfigMailLoader::class, $config['mail_templates'], new Reference('sonatra_mailer.loader.layout_chain'));

        $this->addYamlTemplates($container, 'layout', $config['layout_file_templates']);
        $this->addYamlTemplates($container, 'mail', $config['mail_file_templates']);
    }

    /**
     * Add the templates.
     *
     * @param ContainerBuilder $container The container
     * @param string           $type      The template type
     * @param string           $class     The class name of config loader
     * @param array            $templates The template configs of layouts
     * @param Reference        $reference The reference
     */
    protected function addTemplates(ContainerBuilder $container, $type, $class, array $templates, $reference = null)
    {
        $def = new Definition($class);
        $def->setArguments(array($templates));
        $def->addTag(sprintf('sonatra_mailer.%s_loader', $type));

        if (null !== $reference) {
            $def->addArgument($reference);
        }

        $container->setDefinition(sprintf('sonatra_mailer.loader.config_%s', $type), $def);
    }

    /**
     * Add the file templates.
     *
     * @param ContainerBuilder $container The container
     * @param string           $type      The template type
     * @param array            $templates The template files of layouts
     */
    protected function addYamlTemplates(ContainerBuilder $container, $type, array $templates)
    {
        $def = $container->getDefinition(sprintf('sonatra_mailer.loader.%s_yaml', $type));
        $def->replaceArgument(0, $templates);
    }

    /**
     * Add the config for swiftmailer dkim signer.
     *
     * @param ContainerBuilder $container The container
     * @param array            $config    The config
     */
    protected function addSwiftMailerDkimSigner(ContainerBuilder $container, array $config)
    {
        $prefix = 'sonatra_mailer.transport.signer.swiftmailer_dkim.';

        $container->setParameter($prefix.'private_key_path', $config['private_key_path']);
        $container->setParameter($prefix.'domain', $config['domain']);
        $container->setParameter($prefix.'selector', $config['selector']);
    }
}
