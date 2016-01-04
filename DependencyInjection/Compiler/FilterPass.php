<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Adds all services with the tags "sonatra_mailer.template_filter" and "sonatra_mailer.transport_filter"
 * as arguments of the "sonatra_mailer.loader.filter_registry" service.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class FilterPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('sonatra_mailer.filter_registry')) {
            return;
        }

        $this->addFilters($container, 'template');
        $this->addFilters($container, 'transport');
    }

    /**
     * Inject the template filters in registry.
     *
     * @param ContainerBuilder $container The container
     * @param string           $type      The filter type
     */
    protected function addFilters(ContainerBuilder $container, $type)
    {
        $filters = array();
        $tagName = sprintf('sonatra_mailer.%s_filter', $type);
        $registryName = 'sonatra_mailer.filter_registry';
        $pos = 'transport' === $type ? 1 : 0;

        foreach ($container->findTaggedServiceIds($tagName) as $serviceId => $tags) {
            $priority = isset($tags[0]['priority']) ? $tags[0]['priority'] : 0;
            $filters[$priority][] = new Reference($serviceId);
        }

        // sort by priority and flatten
        if (count($filters) > 0) {
            krsort($filters);
            $filters = call_user_func_array('array_merge', $filters);
        }

        $container->getDefinition($registryName)->replaceArgument($pos, $filters);
    }
}
