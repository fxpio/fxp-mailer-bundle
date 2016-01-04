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
 * Adds all services with the tags "sonatra_mailer.layout_loader" and "sonatra_mailer.mail_loader" as
 * arguments of the "sonatra_mailer.loader.layout_chain" service and "sonatra_mailer.loader.mail_chain" service.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class LoaderPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('sonatra_mailer.loader.layout_chain')
                || !$container->hasDefinition('sonatra_mailer.loader.mail_chain')) {
            return;
        }

        // Build the loaders
        $this->chainLoader($container, 'layout');
        $this->chainLoader($container, 'mail');
    }

    /**
     * Inject the loader in chain loader.
     *
     * @param ContainerBuilder $container The container
     * @param string           $type      The layout or mail type
     */
    protected function chainLoader(ContainerBuilder $container, $type)
    {
        $loaders = array();
        $tagName = sprintf('sonatra_mailer.%s_loader', $type);
        $chainLoaderName = sprintf('sonatra_mailer.loader.%s_chain', $type);

        foreach ($container->findTaggedServiceIds($tagName) as $serviceId => $tags) {
            $priority = isset($tags[0]['priority']) ? $tags[0]['priority'] : 0;
            $loaders[$priority][] = new Reference($serviceId);
        }

        // sort by priority and flatten
        if (count($loaders) > 0) {
            krsort($loaders);
            $loaders = call_user_func_array('array_merge', $loaders);
        }

        $container->getDefinition($chainLoaderName)->replaceArgument(0, $loaders);
    }
}
