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
use Symfony\Component\Yaml\Yaml;

/**
 * Replace all services with the tags "sonatra_mailer.loader.layout_yaml" and
 * "sonatra_mailer.loader.mail_yaml" by "sonatra_mailer.loader.layout_array" service
 * and "sonatra_mailer.loader.mail_array" service.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class OptimizeYamlLoaderPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('sonatra_mailer.loader.layout_yaml')
                || !$container->hasDefinition('sonatra_mailer.loader.mail_yaml')) {
            return;
        }

        $this->optimize($container, 'layout');
        $this->optimize($container, 'mail');

        $container->removeDefinition('sonatra_mailer.loader.layout_yaml');
        $container->removeDefinition('sonatra_mailer.loader.mail_yaml');
    }

    /**
     * Optimize the config loader.
     *
     * @param ContainerBuilder $container The container
     * @param string           $type      The layout or mail type
     */
    protected function optimize(ContainerBuilder $container, $type)
    {
        $serviceId = sprintf('sonatra_mailer.loader.%s_yaml', $type);
        $def = $container->getDefinition($serviceId);

        $templates = $def->getArgument(0);
        $configs = array();

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
    protected function createConfig(ContainerBuilder $container, array $templateConfig)
    {
        $file = ContainerUtil::getRealFile($container, $templateConfig['file']);
        $config = Yaml::parse(file_get_contents($file));

        if (isset($templateConfig['name'])) {
            $config['name'] = $templateConfig['name'];
        }

        return $config;
    }
}
