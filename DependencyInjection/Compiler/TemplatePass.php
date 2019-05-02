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
 * Adds all services with the tags "fxp_mailer.layout" and "fxp_mailer.mail" as
 * arguments of the "fxp_mailer.loader.layout_array" service and "fxp_mailer.loader.mail_array" service.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class TemplatePass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('fxp_mailer.loader.layout_array')
                || !$container->hasDefinition('fxp_mailer.loader.mail_array')) {
            return;
        }

        // Build the loaders
        $this->addTemplate($container, 'layout');
        $this->addTemplate($container, 'mail');
    }

    /**
     * Inject the template in array loader.
     *
     * @param ContainerBuilder $container The container
     * @param string           $type      The layout or mail type
     */
    protected function addTemplate(ContainerBuilder $container, $type): void
    {
        $templates = [];
        $tagName = sprintf('fxp_mailer.%s', $type);
        $arrayLoaderName = sprintf('fxp_mailer.loader.%s_array', $type);

        foreach ($container->findTaggedServiceIds($tagName) as $serviceId => $tags) {
            $templates[] = new Reference($serviceId);
        }

        $container->getDefinition($arrayLoaderName)->replaceArgument(0, $templates);
    }
}
