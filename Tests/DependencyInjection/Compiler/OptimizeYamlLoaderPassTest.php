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

use Sonatra\Bundle\MailerBundle\DependencyInjection\Compiler\OptimizeYamlLoaderPass;
use Sonatra\Component\Mailer\Loader\ArrayLayoutLoader;
use Sonatra\Component\Mailer\Loader\YamlLayoutLoader;
use Sonatra\Component\Mailer\Mailer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Tests for optimize yaml loader pass.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class OptimizeYamlLoaderPassTest extends KernelTestCase
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
     * @var OptimizeYamlLoaderPass
     */
    protected $pass;

    protected function setUp()
    {
        $this->rootDir = sys_get_temp_dir().'/sonatra_mailer_bundle_optimize_yaml_loader_pass';
        $this->fs = new Filesystem();
        $this->pass = new OptimizeYamlLoaderPass();
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
        $this->assertFalse($container->has('sonatra_mailer.loader.layout_yaml'));
        $this->assertFalse($container->has('sonatra_mailer.loader.mail_yaml'));
    }

    public function testProcessWithAddTemplates()
    {
        $container = $this->getContainer();
        $layoutLoaderDef = new Definition(YamlLayoutLoader::class);
        $mailLoaderDef = new Definition(YamlLayoutLoader::class);
        $refMailer = new \ReflectionClass(Mailer::class);
        $mailerBaseDir = dirname($refMailer->getFileName());

        $layoutLoaderDef->setArguments(array(array()));
        $mailLoaderDef->setArguments(array(array()));

        $container->setDefinition('sonatra_mailer.loader.layout_yaml', $layoutLoaderDef);
        $container->setDefinition('sonatra_mailer.loader.mail_yaml', $mailLoaderDef);

        $this->assertCount(0, $layoutLoaderDef->getArgument(0));
        $this->assertCount(0, $mailLoaderDef->getArgument(0));

        // array loader
        $layoutArrayLoaderDef = new Definition(ArrayLayoutLoader::class);
        $mailArrayLoaderDef = new Definition(ArrayLayoutLoader::class);

        $layoutArrayLoaderDef->setArguments(array(array()));
        $mailArrayLoaderDef->setArguments(array(array()));

        $container->setDefinition('sonatra_mailer.loader.layout_array', $layoutArrayLoaderDef);
        $container->setDefinition('sonatra_mailer.loader.mail_array', $mailArrayLoaderDef);

        $this->assertCount(0, $layoutArrayLoaderDef->getArgument(0));
        $this->assertCount(0, $mailArrayLoaderDef->getArgument(0));

        // add mocks
        $layoutLoaderDef->replaceArgument(0, array(
            array(
                'name' => 'layout-test',
                'file' => $mailerBaseDir.'/Tests/Fixtures/loaders/layout.yml',
            ),
        ));
        $mailLoaderDef->replaceArgument(0, array(
            array(
                'name' => 'mail-test',
                'file' => $mailerBaseDir.'/Tests/Fixtures/loaders/mail.yml',
            ),
        ));

        // test
        $this->pass->process($container);
        $this->assertFalse($container->has('sonatra_mailer.loader.layout_yaml'));
        $this->assertFalse($container->has('sonatra_mailer.loader.mail_yaml'));
    }

    /**
     * Gets the container.
     *
     * @return ContainerBuilder
     */
    protected function getContainer()
    {
        $container = new ContainerBuilder(new ParameterBag(array(
            'kernel.cache_dir' => $this->rootDir,
            'kernel.debug' => false,
            'kernel.environment' => 'test',
            'kernel.name' => 'kernel',
            'kernel.root_dir' => $this->rootDir,
            'kernel.charset' => 'UTF-8',
            'kernel.bundles' => array(),
        )));

        return $container;
    }
}
