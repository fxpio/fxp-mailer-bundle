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

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sonatra_mailer');

        $rootNode
            ->children()
                ->scalarNode('layout_class')->defaultValue('Sonatra\Bundle\MailerBundle\Model\LayoutInterface')->end()
                ->scalarNode('mail_class')->defaultValue('Sonatra\Bundle\MailerBundle\Model\MailInterface')->end()
                ->append($this->getLayoutTemplatesNode())
                ->append($this->getMailTemplatesNode())
            ->end()
        ;

        return $treeBuilder;
    }

    protected function getLayoutTemplatesNode()
    {
        $treeBuilder = new TreeBuilder();
        /* @var ArrayNodeDefinition $node */
        $node = $treeBuilder->root('layout_templates');
        $node
            ->fixXmlConfig('layout_template')
            ->useAttributeAsKey('name', false)
            ->normalizeKeys(false)
            ->prototype('array')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('name')->isRequired()->end()
                    ->scalarNode('label')->defaultNull()->end()
                    ->scalarNode('description')->defaultNull()->end()
                    ->scalarNode('enabled')->defaultTrue()->end()
                    ->scalarNode('body')->defaultNull()->end()
                    ->arrayNode('translations')
                        ->useAttributeAsKey('locale', false)
                        ->normalizeKeys(false)
                        ->prototype('array')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('locale')->isRequired()->end()
                                ->scalarNode('label')->defaultNull()->end()
                                ->scalarNode('description')->defaultNull()->end()
                                ->scalarNode('body')->defaultNull()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }

    protected function getMailTemplatesNode()
    {
        $treeBuilder = new TreeBuilder();
        /* @var ArrayNodeDefinition $node */
        $node = $treeBuilder->root('mail_templates');
        $node
            ->fixXmlConfig('mail_template')
            ->useAttributeAsKey('name', false)
            ->normalizeKeys(false)
            ->prototype('array')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('name')->isRequired()->end()
                    ->scalarNode('label')->defaultNull()->end()
                    ->scalarNode('description')->defaultNull()->end()
                    ->scalarNode('enabled')->defaultTrue()->end()
                    ->scalarNode('subject')->defaultNull()->end()
                    ->scalarNode('html_body')->defaultNull()->end()
                    ->scalarNode('body')->defaultNull()->end()
                    ->arrayNode('translations')
                        ->useAttributeAsKey('locale', false)
                        ->normalizeKeys(false)
                        ->prototype('array')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('locale')->isRequired()->end()
                                ->scalarNode('label')->defaultNull()->end()
                                ->scalarNode('description')->defaultNull()->end()
                                ->scalarNode('subject')->defaultNull()->end()
                                ->scalarNode('html_body')->defaultNull()->end()
                                ->scalarNode('body')->defaultNull()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }
}
