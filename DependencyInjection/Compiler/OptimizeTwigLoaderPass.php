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

use Fxp\Bundle\MailerBundle\Util\ConfigTemplate;
use Fxp\Bundle\MailerBundle\Util\ContainerUtil;
use Fxp\Component\Mailer\Model\TwigLayout;
use Fxp\Component\Mailer\Model\TwigLayoutTranslation;
use Fxp\Component\Mailer\Model\TwigMail;
use Fxp\Component\Mailer\Model\TwigMailTranslation;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Replace all services with the tags "fxp_mailer.loader.layout_twig" and
 * "fxp_mailer.loader.mail_twig" by "fxp_mailer.loader.layout_array" service
 * and "fxp_mailer.loader.mail_array" service.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class OptimizeTwigLoaderPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('fxp_mailer.loader.layout_twig')
                || !$container->hasDefinition('fxp_mailer.loader.mail_twig')) {
            return;
        }

        $this->optimize($container, 'layout');
        $this->optimize($container, 'mail');

        $container->removeDefinition('fxp_mailer.loader.mail_twig');
        $container->removeDefinition('fxp_mailer.loader.layout_twig');
    }

    /**
     * Optimize the twig loader.
     *
     * @param ContainerBuilder $container The container
     * @param string           $type      The layout or mail type
     */
    protected function optimize(ContainerBuilder $container, string $type): void
    {
        $templateConfig = new ConfigTemplate(TwigLayout::class, TwigMail::class, TwigLayoutTranslation::class, TwigMailTranslation::class);
        $serviceId = sprintf('fxp_mailer.loader.%s_twig', $type);
        $def = $container->getDefinition($serviceId);

        $configs = $def->getArgument(0);

        ContainerUtil::addTemplates($container, $type, $configs, $templateConfig);
    }
}
