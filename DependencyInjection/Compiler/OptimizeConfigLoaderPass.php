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

use Fxp\Bundle\MailerBundle\Util\ContainerUtil;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Replace all services with the tags "fxp_mailer.loader.layout_config" and
 * "fxp_mailer.loader.mail_config" by "fxp_mailer.loader.layout_array" service
 * and "fxp_mailer.loader.mail_array" service.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class OptimizeConfigLoaderPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('fxp_mailer.loader.layout_config')
                || !$container->hasDefinition('fxp_mailer.loader.mail_config')) {
            return;
        }

        $this->optimize($container, 'layout');
        $this->optimize($container, 'mail');

        $container->removeDefinition('fxp_mailer.loader.mail_config');
        $container->removeDefinition('fxp_mailer.loader.layout_config');
    }

    /**
     * Optimize the config loader.
     *
     * @param ContainerBuilder $container The container
     * @param string           $type      The layout or mail type
     */
    protected function optimize(ContainerBuilder $container, string $type): void
    {
        $serviceId = sprintf('fxp_mailer.loader.%s_config', $type);
        $def = $container->getDefinition($serviceId);

        ContainerUtil::addTemplates($container, $type, $def->getArgument(0));
    }
}
