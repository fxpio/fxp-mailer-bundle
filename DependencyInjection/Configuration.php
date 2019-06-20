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

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('fxp_mailer');
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
            ->append($this->getSmsSenderNode())
            ->append($this->getTwigNode())
            ->end()
        ;

        return $treeBuilder;
    }

    protected function getSmsSenderNode(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('sms_sender');
        /** @var ArrayNodeDefinition $node */
        $node = $treeBuilder->getRootNode();
        $node
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('dsn')->defaultValue('sms://null')->end()
            ->end()
        ;

        return $node;
    }

    protected function getTwigNode(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('twig');
        /** @var ArrayNodeDefinition $node */
        $node = $treeBuilder->getRootNode();
        $node
            ->addDefaultsIfNotSet()
            ->children()
            ->append($this->getTwigSandboxNode())
            ->append($this->getTwigLoaderNode())
            ->booleanNode('enable_unstrict_variables')->defaultTrue()->end()
            ->scalarNode('default_locale')->defaultNull()->end()
            ->end()
        ;

        return $node;
    }

    protected function getTwigSandboxNode(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('sandbox');
        /** @var ArrayNodeDefinition $node */
        $node = $treeBuilder->getRootNode();
        $node
            ->addDefaultsIfNotSet()
            ->children()
            ->append($this->getTwigSandboxSecurityPolicyNode())
            ->arrayNode('available_namespaces')
            ->defaultValue(['doctrine_template_messages'])
            ->scalarPrototype()->end()
            ->end()
            ->end()
        ;

        return $node;
    }

    protected function getTwigSandboxSecurityPolicyNode(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('security_policy');
        /** @var ArrayNodeDefinition $node */
        $node = $treeBuilder->getRootNode();
        $node
            ->addDefaultsIfNotSet()
            ->children()
            ->booleanNode('override')->defaultFalse()->end()
            ->arrayNode('allowed_tags')
            ->scalarPrototype()->end()
            ->end()
            ->arrayNode('allowed_filters')
            ->scalarPrototype()->end()
            ->end()
            ->arrayNode('allowed_methods')
            ->arrayPrototype()
            ->scalarPrototype()->end()
            ->end()
            ->end()
            ->arrayNode('allowed_properties')
            ->arrayPrototype()
            ->scalarPrototype()->end()
            ->end()
            ->end()
            ->arrayNode('allowed_functions')
            ->scalarPrototype()->end()
            ->end()
            ->end()
        ;

        return $node;
    }

    protected function getTwigLoaderNode(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('loaders');
        /** @var ArrayNodeDefinition $node */
        $node = $treeBuilder->getRootNode();
        $node
            ->addDefaultsIfNotSet()
            ->children()
            ->booleanNode('doctrine')->defaultFalse()->end()
            ->end()
        ;

        return $node;
    }
}
