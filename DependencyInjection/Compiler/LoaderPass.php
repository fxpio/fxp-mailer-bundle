<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Bundle\MailerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Adds all services with the tags "fxp_mailer.layout_loader" and "fxp_mailer.mail_loader" as
 * arguments of the "fxp_mailer.loader.layout_chain" service and "fxp_mailer.loader.mail_chain" service.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class LoaderPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('fxp_mailer.loader.layout_chain')
                || !$container->hasDefinition('fxp_mailer.loader.mail_chain')) {
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
        $loaders = [];
        $tagName = sprintf('fxp_mailer.%s_loader', $type);
        $chainLoaderName = sprintf('fxp_mailer.loader.%s_chain', $type);

        foreach ($container->findTaggedServiceIds($tagName) as $serviceId => $tags) {
            $priority = isset($tags[0]['priority']) ? $tags[0]['priority'] : 0;
            $loaders[$priority][] = new Reference($serviceId);
        }

        // sort by priority and flatten
        if (\count($loaders) > 0) {
            krsort($loaders);
            $loaders = \call_user_func_array('array_merge', $loaders);
        }

        $container->getDefinition($chainLoaderName)->replaceArgument(0, $loaders);
    }
}
