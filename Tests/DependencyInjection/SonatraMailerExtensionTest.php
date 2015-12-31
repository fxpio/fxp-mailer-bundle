<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Tests\DependencyInjection;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\DoctrineExtension;
use Sonatra\Bundle\MailerBundle\DependencyInjection\SonatraMailerExtension;
use Sonatra\Bundle\MailerBundle\SonatraMailerBundle;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\FrameworkExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

/**
 * Tests for symfony extension.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class SonatraMailerExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testExtensionExist()
    {
        $container = $this->createContainer();

        $this->assertTrue($container->hasExtension('sonatra_mailer'));
    }

    public function testExtensionLoader()
    {
        $container = $this->createContainer();

        $this->assertTrue($container->hasDefinition('sonatra_mailer.mail_templater'));
        $this->assertTrue($container->hasDefinition('sonatra_mailer.loader.layout_chain'));
        $this->assertTrue($container->hasDefinition('sonatra_mailer.loader.mail_chain'));
        $this->assertFalse($container->hasDefinition('sonatra_mailer.loader.config_layout'));
        $this->assertFalse($container->hasDefinition('sonatra_mailer.loader.config_mail'));
        $this->assertFalse($container->hasDefinition('sonatra_mailer.loader.layout_yaml'));
        $this->assertFalse($container->hasDefinition('sonatra_mailer.loader.mail_yaml'));
    }

    protected function createContainer(array $configs = array())
    {
        $container = new ContainerBuilder(new ParameterBag(array(
            'kernel.bundles' => array(
                'FrameworkBundle' => 'Symfony\\Bundle\\FrameworkBundle\\FrameworkBundle',
                'SonatraMailerBundle' => 'Sonatra\\Bundle\\MailerBundle\\SonatraMailerBundle',
            ),
            'kernel.cache_dir' => sys_get_temp_dir().'/sonatra_mailer_bundle',
            'kernel.debug' => false,
            'kernel.environment' => 'test',
            'kernel.name' => 'kernel',
            'kernel.root_dir' => sys_get_temp_dir().'/sonatra_mailer_bundle',
            'kernel.charset' => 'UTF-8',
        )));

        $sfExt = new FrameworkExtension();
        $doctrineExt = new DoctrineExtension();
        $extension = new SonatraMailerExtension();

        $container->registerExtension($sfExt);
        $container->registerExtension($doctrineExt);
        $container->registerExtension($extension);

        $sfExt->load(array(array()), $container);
        $doctrineExt->load(array($this->getDoctrineConfig()), $container);
        $extension->load($configs, $container);

        $bundle = new SonatraMailerBundle();
        $bundle->build($container);

        $optimizationPasses = array();

        foreach ($container->getCompilerPassConfig()->getOptimizationPasses() as $pass) {
            if (0 === strpos(get_class($pass), 'Sonatra\Bundle\MailerBundle\DependencyInjection\Compiler')) {
                $optimizationPasses[] = $pass;
            }
        }

        $container->getCompilerPassConfig()->setOptimizationPasses($optimizationPasses);
        $container->getCompilerPassConfig()->setRemovingPasses(array());
        $container->compile();

        return $container;
    }

    protected function getDoctrineConfig()
    {
        return array(
            'dbal' => array(
                'default_connection' => 'default',
                'connections' => array(
                    'default' => array(
                        'driver' => 'pdo_sqlite',
                        'path' => '%kernel.cache_dir%/test.db',
                    ),
                ),
            ),
            'orm' => array(
                'auto_generate_proxy_classes' => true,
                'entity_managers' => array(
                    'default' => array(
                        'auto_mapping' => true,
                    ),
                ),
            ),
        );
    }
}
