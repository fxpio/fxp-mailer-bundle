<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Bundle\MailerBundle\Tests\DependencyInjection\Compiler;

use Fxp\Bundle\MailerBundle\DependencyInjection\Compiler\TransportPass;
use Fxp\Component\Mailer\Transport\TransportInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Tests for transport pass.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class TransportPassTest extends KernelTestCase
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
     * @var TransportPass
     */
    protected $pass;

    protected function setUp()
    {
        $this->rootDir = sys_get_temp_dir().'/fxp_mailer_bundle_transport_pass';
        $this->fs = new Filesystem();
        $this->pass = new TransportPass();
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
        $this->assertFalse($container->has('fxp_mailer.transport_registry'));
    }

    public function testProcessAddTransport()
    {
        $container = $this->getContainer();

        $defMailer = new Definition();
        $defMailer->setArguments([null, []]);
        $container->setDefinition('fxp_mailer.mailer', $defMailer);

        $transportMock = $this->getMockBuilder(TransportInterface::class)->getMock();

        $defTransport = new Definition(get_class($transportMock));
        $defTransport->addTag('fxp_mailer.transport');
        $container->setDefinition('test.transport', $defTransport);

        $this->assertCount(0, $defTransport->getMethodCalls());
        $this->pass->process($container);

        $methods = $defTransport->getMethodCalls();
        $this->assertCount(0, $methods);
    }

    /**
     * Gets the container.
     *
     * @return ContainerBuilder
     */
    protected function getContainer()
    {
        $container = new ContainerBuilder(new ParameterBag([
            'kernel.cache_dir' => $this->rootDir,
            'kernel.debug' => false,
            'kernel.environment' => 'test',
            'kernel.name' => 'kernel',
            'kernel.root_dir' => $this->rootDir,
            'kernel.charset' => 'UTF-8',
            'kernel.bundles' => [],
        ]));

        return $container;
    }
}
