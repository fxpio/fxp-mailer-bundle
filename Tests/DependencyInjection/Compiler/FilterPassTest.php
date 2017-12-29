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

use Fxp\Bundle\MailerBundle\DependencyInjection\Compiler\FilterPass;
use Fxp\Component\Mailer\Filter\FilterRegistry;
use Fxp\Component\Mailer\Filter\TemplateFilterInterface;
use Fxp\Component\Mailer\Filter\TransportFilterInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

/**
 * Tests for filter pass.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class FilterPassTest extends KernelTestCase
{
    /**
     * @var FilterPass
     */
    protected $pass;

    protected function setUp()
    {
        $this->pass = new FilterPass();
    }

    protected function tearDown()
    {
        $this->pass = null;
    }

    public function testProcessWithoutService()
    {
        $container = $this->getContainer();

        $this->pass->process($container);
        $this->assertFalse($container->has('fxp_mailer.filter_registry'));
    }

    public function testProcessWithAddFilters()
    {
        $container = $this->getContainer();
        $registryDef = new Definition(FilterRegistry::class);
        $registryDef->setArguments([[], []]);

        $container->setDefinition('fxp_mailer.filter_registry', $registryDef);

        $this->assertCount(0, $registryDef->getArgument(0));
        $this->assertCount(0, $registryDef->getArgument(1));

        // add mocks
        $templateFilter = new Definition(TemplateFilterInterface::class);
        $transportFilter = new Definition(TransportFilterInterface::class);
        $templateFilter->addTag('fxp_mailer.template_filter');
        $transportFilter->addTag('fxp_mailer.transport_filter');

        $container->setDefinition('test.template_filter', $templateFilter);
        $container->setDefinition('test.transport_filter', $transportFilter);

        // test
        $this->pass->process($container);

        $this->assertCount(1, $registryDef->getArgument(0));
        $this->assertCount(1, $registryDef->getArgument(1));
    }

    /**
     * Gets the container.
     *
     * @return ContainerBuilder
     */
    protected function getContainer()
    {
        $container = new ContainerBuilder(new ParameterBag([
            'kernel.debug' => false,
            'kernel.environment' => 'test',
            'kernel.name' => 'kernel',
            'kernel.charset' => 'UTF-8',
            'kernel.bundles' => [],
        ]));

        return $container;
    }
}
