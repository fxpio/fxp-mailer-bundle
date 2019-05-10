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
use Symfony\Component\Yaml\Yaml;

/**
 * Replace all services with the tags "fxp_mailer.loader.template_layout_yaml" and
 * "fxp_mailer.loader.template_mail_yaml" by "fxp_mailer.loader.template_layout_array" service
 * and "fxp_mailer.loader.template_mail_array" service.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class OptimizeYamlTemplateLoaderPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('fxp_mailer.loader.template_layout_yaml')
                || !$container->hasDefinition('fxp_mailer.loader.template_mail_yaml')) {
            return;
        }

        $this->optimize($container, 'layout');
        $this->optimize($container, 'mail');

        $container->removeDefinition('fxp_mailer.loader.template_layout_yaml');
        $container->removeDefinition('fxp_mailer.loader.template_mail_yaml');
    }

    /**
     * Optimize the config loader.
     *
     * @param ContainerBuilder $container The container
     * @param string           $type      The layout or mail type
     */
    protected function optimize(ContainerBuilder $container, string $type): void
    {
        $serviceId = sprintf('fxp_mailer.loader.template_%s_yaml', $type);
        $def = $container->getDefinition($serviceId);

        $templates = $def->getArgument(0);
        $configs = [];

        foreach ($templates as $template) {
            $configs[] = $this->createConfig($container, $template);
        }

        ContainerUtil::addTemplates($container, $type, $configs);
    }

    /**
     * Create the config.
     *
     * @param ContainerBuilder $container
     * @param array            $templateConfig
     *
     * @return array
     */
    protected function createConfig(ContainerBuilder $container, array $templateConfig): array
    {
        $file = ContainerUtil::getRealFile($container, $templateConfig['file']);
        $config = Yaml::parse(file_get_contents($file));

        if (isset($templateConfig['name'])) {
            $config['name'] = $templateConfig['name'];
        }

        return $config;
    }
}
