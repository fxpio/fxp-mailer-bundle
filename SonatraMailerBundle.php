<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Sonatra\Bundle\MailerBundle\DependencyInjection\Compiler\LoaderPass;
use Sonatra\Bundle\MailerBundle\DependencyInjection\Compiler\OptimizeConfigLoaderPass;
use Sonatra\Bundle\MailerBundle\DependencyInjection\Compiler\OptimizeYamlLoaderPass;
use Sonatra\Bundle\MailerBundle\DependencyInjection\Compiler\SignerPass;
use Sonatra\Bundle\MailerBundle\DependencyInjection\Compiler\TemplatePass;
use Sonatra\Bundle\MailerBundle\DependencyInjection\Compiler\TransportPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class SonatraMailerBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $ormCompilerClass = 'Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass';

        if (class_exists($ormCompilerClass)) {
            $ref = new \ReflectionClass($this);
            $container->addCompilerPass(
                DoctrineOrmMappingsPass::createXmlMappingDriver(
                    array(
                        realpath(dirname($ref->getFileName()).'/Resources/config/doctrine/model') => 'Sonatra\Bundle\MailerBundle\Model',
                    )
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
    protected function addCompilerPasses(ContainerBuilder $container)
    {
        $container->addCompilerPass(new LoaderPass());
        $container->addCompilerPass(new TransportPass());
        $container->addCompilerPass(new SignerPass());
        $container->addCompilerPass(new OptimizeConfigLoaderPass(), PassConfig::TYPE_OPTIMIZE);
        $container->addCompilerPass(new OptimizeYamlLoaderPass(), PassConfig::TYPE_OPTIMIZE);
        $container->addCompilerPass(new TemplatePass(), PassConfig::TYPE_OPTIMIZE);
    }
}
