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

use Sonatra\Bundle\MailerBundle\Transport\Signer\TransportSignerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Adds all services with the tags "sonatra_mailer.transport" as arguments of
 * the "sonatra_mailer.mailer" service.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class TransportPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('sonatra_mailer.mailer')) {
            return;
        }

        $transports = array();

        foreach ($container->findTaggedServiceIds('sonatra_mailer.transport') as $serviceId => $tags) {
            $transports[] = new Reference($serviceId);
            $this->addSignerRegistry($container, $serviceId);
        }

        $container->getDefinition('sonatra_mailer.mailer')->replaceArgument(1, $transports);

        $this->clean($container);
    }

    /**
     * Add the signer registry in service compatible with the TransportSignerInterface interface.
     *
     * @param ContainerBuilder $container The container
     * @param string           $serviceId The service id
     */
    protected function addSignerRegistry(ContainerBuilder $container, $serviceId)
    {
        $def = $container->getDefinition($serviceId);
        $implements = class_implements($this->getRealClassName($container, $def->getClass()));

        if (in_array(TransportSignerInterface::class, $implements)) {
            $def->addMethodCall('setSignerRegistry', array(new Reference('sonatra_mailer.signer_registry')));
            $signers = $container->getParameter('sonatra_mailer.transport_signers');

            if (isset($signers[$serviceId])) {
                $def->addMethodCall('setSigner', array($signers[$serviceId]['signer']));
            }
        }
    }

    /**
     * Get the real class name.
     *
     * @param ContainerBuilder $container The container
     * @param string           $classname The class name or the parameter name of classname
     *
     * @return string
     */
    protected function getRealClassName(ContainerBuilder $container, $classname)
    {
        return 0 === strpos($classname, '%')
            ? $container->getParameter(trim($classname, '%'))
            : $classname;
    }

    /**
     * Clean the parameters.
     *
     * @param ContainerBuilder $container The container
     */
    protected function clean(ContainerBuilder $container)
    {
        /* @var ParameterBag $pb */
        $pb = $container->getParameterBag();
        $pb->remove('sonatra_mailer.transport_signers');
    }
}
