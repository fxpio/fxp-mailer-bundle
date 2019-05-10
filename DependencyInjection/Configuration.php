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

use Fxp\Component\Mailer\MailTypes;
use Fxp\Component\Mailer\Model\LayoutInterface;
use Fxp\Component\Mailer\Model\MailInterface;
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
            ->children()
            ->scalarNode('layout_class')->defaultValue(LayoutInterface::class)->end()
            ->scalarNode('mail_class')->defaultValue(MailInterface::class)->end()
            ->append($this->getLayoutTemplatesNode())
            ->append($this->getMailTemplatesNode())
            ->append($this->getTransportNode())
            ->append($this->getFilterNode())
            ->end()
        ;

        return $treeBuilder;
    }

    protected function getLayoutTemplatesNode(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('layout_templates');
        /** @var ArrayNodeDefinition $node */
        $node = $treeBuilder->getRootNode();
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

    protected function getMailTemplatesNode(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('mail_templates');
        /** @var ArrayNodeDefinition $node */
        $node = $treeBuilder->getRootNode();
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

    protected function getTransportNode(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('transports');
        /** @var ArrayNodeDefinition $node */
        $node = $treeBuilder->getRootNode();
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

    protected function getSwiftMailerEmbedImageNode(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('embed_image');
        /** @var ArrayNodeDefinition $node */
        $node = $treeBuilder->getRootNode();
        $node
            ->addDefaultsIfNotSet()
            ->canBeEnabled()
            ->children()
            ->scalarNode('web_dir')->defaultNull()->end()
            ->scalarNode('host_pattern')->defaultValue('/(.*)+/')->end()
            ->end()
        ;

        return $node;
    }

    protected function getSwiftMailerDkimSignerNode(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('dkim_signer');
        /** @var ArrayNodeDefinition $node */
        $node = $treeBuilder->getRootNode();
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

    protected function getFilterNode(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('filters');
        /** @var ArrayNodeDefinition $node */
        $node = $treeBuilder->getRootNode();
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

    protected function getFilterTypeNode(string $type): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder($type.'s');
        /** @var ArrayNodeDefinition $node */
        $node = $treeBuilder->getRootNode();
        $node
            ->fixXmlConfig($type)
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('name')
            ->prototype('variable')
            ->treatNullLike([])
            ->validate()
            ->ifTrue(static function ($v) {
                return !\is_array($v);
            })
            ->thenInvalid('The fxp_mailer.filters.'.$type.'s config %s must be either null or an array.')
            ->end()
            ->end()
        ;

        return $node;
    }
}
