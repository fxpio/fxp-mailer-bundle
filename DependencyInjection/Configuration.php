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

use Sonatra\Component\Mailer\MailTypes;
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
                ->scalarNode('layout_class')->defaultValue('Sonatra\Component\Mailer\Model\LayoutInterface')->end()
                ->scalarNode('mail_class')->defaultValue('Sonatra\Component\Mailer\Model\MailInterface')->end()
                ->append($this->getLayoutTemplatesNode())
                ->append($this->getMailTemplatesNode())
                ->append($this->getTransportNode())
                ->append($this->getFilterNode())
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
                    ->scalarNode('loader')->defaultValue('config')->end()
                    ->scalarNode('file')->defaultNull()->end()
                    ->scalarNode('label')->defaultNull()->end()
                    ->scalarNode('description')->defaultNull()->end()
                    ->scalarNode('enabled')->defaultTrue()->end()
                    ->scalarNode('body')->defaultNull()->end()
                    ->scalarNode('translation_domain')->defaultNull()->end()
                    ->arrayNode('translations')
                        ->useAttributeAsKey('locale', false)
                        ->normalizeKeys(false)
                        ->prototype('array')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('locale')->isRequired()->end()
                                ->scalarNode('file')->defaultNull()->end()
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
                    ->scalarNode('loader')->defaultValue('config')->end()
                    ->scalarNode('file')->defaultNull()->end()
                    ->scalarNode('label')->defaultNull()->end()
                    ->scalarNode('description')->defaultNull()->end()
                    ->scalarNode('type')->defaultValue(MailTypes::TYPE_ALL)->end()
                    ->scalarNode('enabled')->defaultTrue()->end()
                    ->scalarNode('subject')->defaultNull()->end()
                    ->scalarNode('html_body')->defaultNull()->end()
                    ->scalarNode('body')->defaultNull()->end()
                    ->scalarNode('layout')->defaultNull()->end()
                    ->scalarNode('translation_domain')->defaultNull()->end()
                    ->arrayNode('translations')
                        ->useAttributeAsKey('locale', false)
                        ->normalizeKeys(false)
                        ->prototype('array')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('locale')->isRequired()->end()
                                ->scalarNode('file')->defaultNull()->end()
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

    protected function getTransportNode()
    {
        $treeBuilder = new TreeBuilder();
        /* @var ArrayNodeDefinition $node */
        $node = $treeBuilder->root('transports');
        $node
            ->fixXmlConfig('transport')
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('swiftmailer')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->append($this->getSwiftMailerEmbedImageNode())
                        ->append($this->getSwiftMailerDkimSignerNode())
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }

    protected function getSwiftMailerEmbedImageNode()
    {
        $treeBuilder = new TreeBuilder();
        /* @var ArrayNodeDefinition $node */
        $node = $treeBuilder->root('embed_image');
        $node
            ->addDefaultsIfNotSet()
            ->canBeEnabled()
            ->children()
                ->scalarNode('host_pattern')->defaultValue('/(.*)+/')->end()
            ->end()
        ;

        return $node;
    }

    protected function getSwiftMailerDkimSignerNode()
    {
        $treeBuilder = new TreeBuilder();
        /* @var ArrayNodeDefinition $node */
        $node = $treeBuilder->root('dkim_signer');
        $node
            ->addDefaultsIfNotSet()
            ->canBeEnabled()
            ->children()
                ->scalarNode('private_key_path')->defaultNull()->end()
                ->scalarNode('domain')->defaultNull()->end()
                ->scalarNode('selector')->defaultNull()->end()
            ->end()
        ;

        return $node;
    }

    protected function getFilterNode()
    {
        $treeBuilder = new TreeBuilder();
        /* @var ArrayNodeDefinition $node */
        $node = $treeBuilder->root('filters');
        $node
            ->fixXmlConfig('filter')
            ->addDefaultsIfNotSet()
            ->children()
                ->append($this->getFilterTypeNode('template'))
                ->append($this->getFilterTypeNode('transport'))
            ->end()
        ;

        return $node;
    }

    protected function getFilterTypeNode($type)
    {
        $treeBuilder = new TreeBuilder();
        /* @var ArrayNodeDefinition $node */
        $node = $treeBuilder->root($type.'s');
        $node
            ->fixXmlConfig($type)
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('name')
            ->prototype('variable')
                ->treatNullLike(array())
                ->validate()
                    ->ifTrue(function ($v) {
                        return !is_array($v);
                    })
                    ->thenInvalid('The sonatra_mailer.filters.'.$type.'s config %s must be either null or an array.')
                ->end()
            ->end()
        ;

        return $node;
    }
}
