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

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Adds all services with the tags "sonatra_mailer.layout" and "sonatra_mailer.mail" as
 * arguments of the "sonatra_mailer.loader.layout_array" service and "sonatra_mailer.loader.mail_array" service.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class TemplatePass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('sonatra_mailer.loader.layout_array')
                || !$container->hasDefinition('sonatra_mailer.loader.mail_array')) {
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
    protected function addTemplate(ContainerBuilder $container, $type)
    {
        $templates = array();
        $tagName = sprintf('sonatra_mailer.%s', $type);
        $arrayLoaderName = sprintf('sonatra_mailer.loader.%s_array', $type);

        foreach ($container->findTaggedServiceIds($tagName) as $serviceId => $tags) {
            $templates[] = new Reference($serviceId);
        }

        $container->getDefinition($arrayLoaderName)->replaceArgument(0, $templates);
    }
}
