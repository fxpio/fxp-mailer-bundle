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
 * Adds all services with the tags "sonatra_mailer.signer" as arguments of
 * the "sonatra_mailer.signer_registry" service.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class SignerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('sonatra_mailer.signer_registry')) {
            return;
        }

        $signers = array();

        foreach ($container->findTaggedServiceIds('sonatra_mailer.signer') as $serviceId => $tags) {
            $signers[] = new Reference($serviceId);
        }

        $container->getDefinition('sonatra_mailer.signer_registry')->replaceArgument(0, $signers);
    }
}
