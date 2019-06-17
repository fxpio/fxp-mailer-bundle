<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Bundle\MailerBundle\DependencyInjection;

use Fxp\Component\Mailer\TwigSecurityPolicies;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Mailer\Mailer;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class FxpMailerExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $this->configureMailer($loader);
        $this->configureTwig($container, $loader, $config['twig']);
    }

    /**
     * @param LoaderInterface $loader
     *
     * @throws
     */
    private function configureMailer(LoaderInterface $loader): void
    {
        $loader->load('mailer.xml');

        if (class_exists(Mailer::class)) {
            $loader->load('mailer_symfony_mailer.xml');
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param LoaderInterface  $loader
     * @param array            $config
     *
     * @throws
     */
    private function configureTwig(ContainerBuilder $container, LoaderInterface $loader, array $config): void
    {
        if (class_exists(TwigBundle::class)) {
            $loader->load('twig.xml');

            $this->configureTwigSandbox($container, $config['sandbox']);
            $this->configureTwigEmail($container, $loader, $config);
            $this->configureTwigDefaultLocale($container, $config);
            $this->configureTwigLoaders($loader, $config['loaders']);
        }
    }

    private function configureTwigSandbox(ContainerBuilder $container, array $config): void
    {
        $securityPolicy = $this->mergeSecurityPolicy($config['security_policy']);
        $container->getDefinition('fxp_mailer.twig.sandbox.security_policy')
            ->replaceArgument(0, $securityPolicy['allowed_tags'])
            ->replaceArgument(1, $securityPolicy['allowed_filters'])
            ->replaceArgument(2, $securityPolicy['allowed_methods'])
            ->replaceArgument(3, $securityPolicy['allowed_properties'])
            ->replaceArgument(4, $securityPolicy['allowed_functions'])
        ;

        $container->getDefinition('fxp_mailer.twig.loader.sandbox')
            ->replaceArgument(1, $config['available_namespaces'])
        ;
    }

    /**
     * @param ContainerBuilder $container
     * @param LoaderInterface  $loader
     * @param array            $config
     *
     * @throws
     */
    private function configureTwigEmail(ContainerBuilder $container, LoaderInterface $loader, array $config): void
    {
        if (class_exists(Mailer::class)) {
            $loader->load('twig_symfony_mailer.xml');

            if (!$config['enable_unstrict_variables']) {
                $container->removeDefinition('fxp_mailer.twig.symfony_mailer.unstrict_body_renderer');
            }
        }
    }

    private function configureTwigDefaultLocale(ContainerBuilder $container, array $config): void
    {
        $defaultLocale = $config['default_locale'];

        foreach (['locale_fallback', 'locale', 'default_locale'] as $parameter) {
            if ($container->hasParameter($parameter)) {
                $defaultLocale = $parameter;

                break;
            }
        }

        if (null !== $defaultLocale) {
            $container->getDefinition('fxp_mailer.twig.loader.filesystem_template')
                ->replaceArgument(1, '%'.$defaultLocale.'%')
            ;
        }
    }

    /**
     * @param LoaderInterface $loader
     * @param array           $config
     *
     * @throws
     */
    private function configureTwigLoaders(LoaderInterface $loader, array $config): void
    {
        if ($config['doctrine']) {
            $loader->load('twig_doctrine.xml');
        }
    }

    private function mergeSecurityPolicy(array $config): array
    {
        if (!$config['override']) {
            $config['allowed_tags'] = array_unique(array_merge(TwigSecurityPolicies::ALLOWED_TAGS, $config['allowed_tags']));
            $config['allowed_filters'] = array_unique(array_merge(TwigSecurityPolicies::ALLOWED_FILTERS, $config['allowed_filters']));
            $config['allowed_methods'] = array_merge(TwigSecurityPolicies::ALLOWED_METHODS, $config['allowed_methods']);
            $config['allowed_properties'] = array_merge(TwigSecurityPolicies::ALLOWED_PROPERTIES, $config['allowed_properties']);
            $config['allowed_functions'] = array_unique(array_merge(TwigSecurityPolicies::ALLOWED_FUNCTIONS, $config['allowed_functions']));
        }

        return $config;
    }
}
