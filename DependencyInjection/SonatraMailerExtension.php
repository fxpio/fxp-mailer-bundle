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

use Sonatra\Component\Mailer\Loader\ConfigLayoutLoader;
use Sonatra\Component\Mailer\Loader\ConfigMailLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

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

        $this->loadConfigs($loader);
        $this->configureTransport($container, $loader, $config);
        $this->addTemplates($container, 'layout', ConfigLayoutLoader::class, $config['layout_templates']);
        $this->addTemplates($container, 'mail', ConfigMailLoader::class, $config['mail_templates'], new Reference('sonatra_mailer.loader.layout_chain'));
        $this->addFilters($container, $loader, 'template', $config['filters']['templates']);
        $this->addFilters($container, $loader, 'transport', $config['filters']['transports']);
    }

    /**
     * Load the configs.
     *
     * @param Loader\XmlFileLoader $loader The loader
     */
    protected function loadConfigs(Loader\XmlFileLoader $loader)
    {
        $loader->load('mailer.xml');
        $loader->load('templater.xml');
        $loader->load('filter.xml');
        $loader->load('doctrine_loader.xml');
        $loader->load('twig.xml');
    }

    /**
     * Configure the transport.
     *
     * @param ContainerBuilder     $container The container
     * @param Loader\XmlFileLoader $loader    The config loader
     * @param array                $config    The config
     */
    protected function configureTransport(ContainerBuilder $container, Loader\XmlFileLoader $loader, array $config)
    {
        if (class_exists('Swift_Message')) {
            $loader->load('transport_swiftmailer.xml');

            if ($config['transports']['swiftmailer']['dkim_signer']['enabled']) {
                $loader->load('transport_swiftmailer_dkim_signer.xml');
                $dkimConfig = $config['transports']['swiftmailer']['dkim_signer'];
                $prefix = 'sonatra_mailer.transport.swiftmailer.dkim_signer.';
                $container->setParameter($prefix.'private_key_path', $dkimConfig['private_key_path']);
                $container->setParameter($prefix.'domain', $dkimConfig['domain']);
                $container->setParameter($prefix.'selector', $dkimConfig['selector']);
            }

            if ($config['transports']['swiftmailer']['embed_image']['enabled']) {
                $loader->load('transport_swiftmailer_embed_image.xml');
                $embedConfig = $config['transports']['swiftmailer']['embed_image'];
                $prefix = 'sonatra_mailer.transport.swiftmailer.embed_image.';
                $container->setParameter($prefix.'web_dir', $this->getWebDir($container, $embedConfig['web_dir']));
                $container->setParameter($prefix.'host_pattern', $embedConfig['host_pattern']);
            }
        }
    }

    /**
     * Add the templates.
     *
     * Not attached with tag because removing on the optimization.
     *
     * @param ContainerBuilder $container The container
     * @param string           $type      The template type
     * @param string           $class     The class name of config loader
     * @param array            $templates The template configs of layouts
     * @param Reference        $reference The reference
     */
    protected function addTemplates(ContainerBuilder $container, $type, $class, array $templates, $reference = null)
    {
        $loaderTypes = array();

        foreach ($templates as $template) {
            $loader = isset($template['loader']) ? $template['loader'] : 'config';
            $loaderTypes[$loader][] = $template;
        }

        foreach ($loaderTypes as $loader => $loaderTemplate) {
            $def = new Definition($class);
            $def->setArguments(array($loaderTemplate));

            if (null !== $reference) {
                $def->addArgument($reference);
            }

            $container->setDefinition(sprintf('sonatra_mailer.loader.%s_%s', $type, $loader), $def);
        }
    }

    /**
     * Add the filters.
     *
     * @param ContainerBuilder     $container The container
     * @param Loader\XmlFileLoader $loader    The xml loader
     * @param string               $type      The filter type
     * @param array[]              $filters   The filters configs
     */
    protected function addFilters(ContainerBuilder $container, Loader\XmlFileLoader $loader,
                                  $type, array $filters)
    {
        foreach ($filters as $name => $filter) {
            $loader->load('filters/'.$type.'s/'.$name.'.xml');

            foreach ($filter as $key => $value) {
                $container->setParameter('sonatra_mailer.filter.'.$type.'.'.$name.'.'.$key, $value);
            }
        }
    }

    /**
     * Get the web directory.
     *
     * @param ContainerBuilder $container The container
     * @param string|null      $webDir    The web directory
     *
     * @return string
     */
    protected function getWebDir(ContainerBuilder $container, $webDir = null)
    {
        if (null === $webDir) {
            $projectDir = $container->getParameter('kernel.project_dir');
            $webDir = is_dir($projectDir.'/public') ? $projectDir.'/public' : $projectDir.'/web';
        }

        return $webDir;
    }
}
