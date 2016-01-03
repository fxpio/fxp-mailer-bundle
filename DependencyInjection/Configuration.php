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

use Sonatra\Bundle\MailerBundle\MailTypes;
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
                ->append($this->getTransportSignerNode())
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

    protected function getTransportSignerNode()
    {
        $treeBuilder = new TreeBuilder();
        /* @var ArrayNodeDefinition $node */
        $node = $treeBuilder->root('transport_signers');
        $node
            ->fixXmlConfig('transport_signer')
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('signers')
                    ->fixXmlConfig('signer')
                    ->useAttributeAsKey('service_id', false)
                    ->normalizeKeys(false)
                    ->prototype('array')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('service_id')->isRequired()->end()
                            ->scalarNode('signer')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('swiftmailer_dkim')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('private_key_path')->defaultNull()->end()
                        ->scalarNode('domain')->defaultNull()->end()
                        ->scalarNode('selector')->defaultNull()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }
}
