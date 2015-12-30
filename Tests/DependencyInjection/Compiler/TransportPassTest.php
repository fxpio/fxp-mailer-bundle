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

use Sonatra\Bundle\MailerBundle\DependencyInjection\Compiler\TransportPass;
use Sonatra\Bundle\MailerBundle\Transport\Signer\TransportSignerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Tests for transport pass.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
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
        $this->rootDir = sys_get_temp_dir().'/sonatra_mailer_bundle_transport_pass';
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
        $this->assertFalse($container->has('sonatra_mailer.transport_registry'));
    }

    public function testProcessAddSignerRegistryInTransport()
    {
        $container = $this->getContainer();

        $defMailer = new Definition();
        $defMailer->setArguments(array(null, array()));
        $container->setDefinition('sonatra_mailer.mailer', $defMailer);

        $transportMock = $this->getMock(TransportSignerInterface::class);

        $defTransport = new Definition(get_class($transportMock));
        $defTransport->addTag('sonatra_mailer.transport');
        $container->setDefinition('test.transport', $defTransport);

        $signerConfig = array(
            'service_id' => 'test.transport',
            'signer' => 'test',
        );
        $container->setParameter('sonatra_mailer.transport_signers', array('test.transport' => $signerConfig));

        $this->assertCount(0, $defTransport->getMethodCalls());
        $this->pass->process($container);

        $methods = $defTransport->getMethodCalls();
        $this->assertCount(2, $methods);

        $this->assertSame('setSignerRegistry', $methods[0][0]);
        $this->assertCount(1, $methods[0][1]);
        $this->assertInstanceOf(Reference::class, $methods[0][1][0]);

        $this->assertSame('setSigner', $methods[1][0]);
        $this->assertCount(1, $methods[1][1]);
        $this->assertSame('test', $methods[1][1][0]);
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
