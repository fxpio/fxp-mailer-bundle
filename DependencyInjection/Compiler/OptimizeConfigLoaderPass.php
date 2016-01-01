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

use Sonatra\Bundle\MailerBundle\Util\ContainerUtil;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Replace all services with the tags "sonatra_mailer.loader.layout_config" and
 * "sonatra_mailer.loader.mail_config" by "sonatra_mailer.loader.layout_array" service
 * and "sonatra_mailer.loader.mail_array" service.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class OptimizeConfigLoaderPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('sonatra_mailer.loader.layout_config')
                || !$container->hasDefinition('sonatra_mailer.loader.mail_config')) {
            return;
        }

        $this->optimize($container, 'layout');
        $this->optimize($container, 'mail');

        $container->removeDefinition('sonatra_mailer.loader.mail_config');
        $container->removeDefinition('sonatra_mailer.loader.layout_config');
    }

    /**
     * Optimize the config loader.
     *
     * @param ContainerBuilder $container The container
     * @param string           $type      The layout or mail type
     */
    protected function optimize(ContainerBuilder $container, $type)
    {
        $serviceId = sprintf('sonatra_mailer.loader.%s_config', $type);
        $def = $container->getDefinition($serviceId);

        ContainerUtil::addTemplates($container, $type, $def->getArgument(0));
    }
}
