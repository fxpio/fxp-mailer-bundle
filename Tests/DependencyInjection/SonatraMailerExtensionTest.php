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
        $this->assertTrue($container->hasDefinition('sonatra_mailer.loader.layout_yaml'));
        $this->assertTrue($container->hasDefinition('sonatra_mailer.loader.mail_yaml'));
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
        $extension = new SonatraMailerExtension();

        $container->registerExtension($sfExt);
        $container->registerExtension($extension);

        $sfExt->load(array(array()), $container);
        $extension->load($configs, $container);

        $bundle = new SonatraMailerBundle();
        $bundle->build($container);

        $container->getCompilerPassConfig()->setOptimizationPasses(array());
        $container->getCompilerPassConfig()->setRemovingPasses(array());
        $container->compile();

        return $container;
    }
}
