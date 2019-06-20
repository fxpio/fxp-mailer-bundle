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

use Fxp\Bundle\MailerBundle\DependencyInjection\Compiler\TransporterPass;
use Fxp\Component\Mailer\Mailer;
use Fxp\Component\Mailer\Transporter\TransporterInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

/**
 * Tests for transport pass.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 *
 * @internal
 */
final class TransporterPassTest extends KernelTestCase
{
    /**
     * @var TransporterPass
     */
    protected $pass;

    protected function setUp(): void
    {
        $this->pass = new TransporterPass();
    }

    protected function tearDown(): void
    {
        $this->pass = null;
    }

    public function testProcessWithoutService(): void
    {
        $container = $this->getContainer();

        $this->pass->process($container);
        static::assertFalse($container->has('fxp_mailer.mailer'));
    }

    public function testProcessWithAddTransporters(): void
    {
        $container = $this->getContainer();
        $mailerDef = new Definition(Mailer::class);
        $mailerDef->setArguments([[]]);

        $container->setDefinition('fxp_mailer.mailer', $mailerDef);

        static::assertCount(0, $mailerDef->getArgument(0));

        // add mocks
        $transporter = new Definition(TransporterInterface::class);
        $transporter->addTag('fxp_mailer.transporter');

        $container->setDefinition('test.transporter', $transporter);

        // test
        $this->pass->process($container);

        static::assertCount(1, $mailerDef->getArgument(0));
    }

    /**
     * Gets the container.
     *
     * @return ContainerBuilder
     */
    protected function getContainer(): ContainerBuilder
    {
        return new ContainerBuilder(new ParameterBag([
            'kernel.debug' => false,
            'kernel.environment' => 'test',
            'kernel.name' => 'kernel',
            'kernel.charset' => 'UTF-8',
            'kernel.bundles' => [],
        ]));
    }
}
