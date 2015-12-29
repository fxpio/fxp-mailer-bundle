<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Tests\DependencyInjection\Compiler;

use Sonatra\Bundle\MailerBundle\DependencyInjection\Compiler\LoaderPass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Tests for loader pass.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class LoaderPassTest extends KernelTestCase
{
    /**
     * @var string
     */
    protected $rootDir;

    /**
     * @var Filesystem
     */
    protected $fs;

    /**
     * @var LoaderPass
     */
    protected $pass;

    protected function setUp()
    {
        $this->rootDir = sys_get_temp_dir().'/sonatra_mailer_bundle_loader_pass';
        $this->fs = new Filesystem();
        $this->pass = new LoaderPass();
    }

    protected function tearDown()
    {
        $this->fs->remove($this->rootDir);
        $this->pass = null;
    }

    public function testProcessWithoutService()
    {
        $container = $this->getContainer();

        $this->pass->process($container);
        $this->assertFalse($container->has('sonatra_mailer.loader.layout_chain'));
        $this->assertFalse($container->has('sonatra_mailer.loader.mail_chain'));
    }

    /**
     * Gets the container.
     *
     * @param array $bundles
     *
     * @return ContainerBuilder
     */
    protected function getContainer(array $bundles = array())
    {
        $container = new ContainerBuilder(new ParameterBag(array(
            'kernel.cache_dir' => $this->rootDir,
            'kernel.debug' => false,
            'kernel.environment' => 'test',
            'kernel.name' => 'kernel',
            'kernel.root_dir' => $this->rootDir,
            'kernel.charset' => 'UTF-8',
            'kernel.bundles' => $bundles,
        )));

        if (count($bundles) > 0) {
            $crDef = new Definition('Sonatra\Bundle\ResourceBundle\Converter\ConverterRegistry');
            $crDef->addArgument(array());
            $container->setDefinition('sonatra_resource.converter_registry', $crDef);

            $jcDef = new Definition('Sonatra\Bundle\ResourceBundle\Converter\JsonConverter');
            $jcDef->addTag('sonatra_resource.converter');
            $container->setDefinition('sonatra_resource.converter.json', $jcDef);
        }

        return $container;
    }
}
