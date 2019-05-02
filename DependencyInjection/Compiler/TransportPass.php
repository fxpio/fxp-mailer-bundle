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
 * Adds all services with the tags "fxp_mailer.transport" as arguments of
 * the "fxp_mailer.mailer" service.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class TransportPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('fxp_mailer.mailer')) {
            return;
        }

        $transports = [];

        foreach ($container->findTaggedServiceIds('fxp_mailer.transport') as $serviceId => $tags) {
            $transports[] = new Reference($serviceId);
        }

        $container->getDefinition('fxp_mailer.mailer')->replaceArgument(1, $transports);
    }
}
