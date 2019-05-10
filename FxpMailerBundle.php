<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Bundle\MailerBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Fxp\Bundle\MailerBundle\DependencyInjection\Compiler\FilterPass;
use Fxp\Bundle\MailerBundle\DependencyInjection\Compiler\OptimizeConfigTemplateLoaderPass;
use Fxp\Bundle\MailerBundle\DependencyInjection\Compiler\OptimizeTwigTemplateLoaderPass;
use Fxp\Bundle\MailerBundle\DependencyInjection\Compiler\OptimizeYamlTemplateLoaderPass;
use Fxp\Bundle\MailerBundle\DependencyInjection\Compiler\TemplateLoaderPass;
use Fxp\Bundle\MailerBundle\DependencyInjection\Compiler\TemplatePass;
use Fxp\Bundle\MailerBundle\DependencyInjection\Compiler\TransportPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class FxpMailerBundle extends Bundle
{
    /**
     * {@inheritdoc}
     *
     * @throws
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        if (class_exists(DoctrineOrmMappingsPass::class)) {
            $ref = new \ReflectionClass($this);
            $container->addCompilerPass(
                DoctrineOrmMappingsPass::createXmlMappingDriver(
                    [
                        realpath(\dirname($ref->getFileName()).'/Resources/config/doctrine/model') => 'Fxp\Component\Mailer\Model',
                    ]
                )
            );
        }

        $this->addCompilerPasses($container);
    }

    /**
     * Add the compiler passes.
     *
     * @param ContainerBuilder $container The container
     */
    protected function addCompilerPasses(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new TemplateLoaderPass());
        $container->addCompilerPass(new TransportPass());
        $container->addCompilerPass(new FilterPass());
        $container->addCompilerPass(new OptimizeConfigTemplateLoaderPass(), PassConfig::TYPE_OPTIMIZE);
        $container->addCompilerPass(new OptimizeYamlTemplateLoaderPass(), PassConfig::TYPE_OPTIMIZE);
        $container->addCompilerPass(new OptimizeTwigTemplateLoaderPass(), PassConfig::TYPE_OPTIMIZE);
        $container->addCompilerPass(new TemplatePass(), PassConfig::TYPE_OPTIMIZE);
    }
}
