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

use Sonatra\Bundle\MailerBundle\Util\ConfigTemplate;
use Sonatra\Bundle\MailerBundle\Util\ContainerUtil;
use Sonatra\Component\Mailer\Model\TwigLayout;
use Sonatra\Component\Mailer\Model\TwigLayoutTranslation;
use Sonatra\Component\Mailer\Model\TwigMail;
use Sonatra\Component\Mailer\Model\TwigMailTranslation;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Replace all services with the tags "sonatra_mailer.loader.layout_twig" and
 * "sonatra_mailer.loader.mail_twig" by "sonatra_mailer.loader.layout_array" service
 * and "sonatra_mailer.loader.mail_array" service.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class OptimizeTwigLoaderPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('sonatra_mailer.loader.layout_twig')
                || !$container->hasDefinition('sonatra_mailer.loader.mail_twig')) {
            return;
        }

        $this->optimize($container, 'layout');
        $this->optimize($container, 'mail');

        $container->removeDefinition('sonatra_mailer.loader.mail_twig');
        $container->removeDefinition('sonatra_mailer.loader.layout_twig');
    }

    /**
     * Optimize the twig loader.
     *
     * @param ContainerBuilder $container The container
     * @param string           $type      The layout or mail type
     */
    protected function optimize(ContainerBuilder $container, $type)
    {
        $templateConfig = new ConfigTemplate(TwigLayout::class, TwigMail::class, TwigLayoutTranslation::class, TwigMailTranslation::class);
        $serviceId = sprintf('sonatra_mailer.loader.%s_twig', $type);
        $def = $container->getDefinition($serviceId);

        $configs = $def->getArgument(0);

        ContainerUtil::addTemplates($container, $type, $configs, $templateConfig);
    }
}
