<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Tests\Filter;

use Sonatra\Bundle\MailerBundle\Filter\FilterRegistry;
use Sonatra\Bundle\MailerBundle\Filter\TemplateFilterInterface;
use Sonatra\Bundle\MailerBundle\Filter\TransportFilterInterface;

/**
 * Tests for filter registry.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class FilterRegistryTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $templateFilter = $this->getMock(TemplateFilterInterface::class);
        $transportFilter = $this->getMock(TransportFilterInterface::class);

        $registry = new FilterRegistry(array($templateFilter), array($transportFilter));

        $templateFilters = $registry->getTemplateFilters();
        $transportFilters = $registry->getTransportFilters();

        $this->assertCount(1, $templateFilters);
        $this->assertCount(1, $transportFilters);

        $this->assertSame($templateFilter, $templateFilters[0]);
        $this->assertSame($transportFilter, $transportFilters[0]);
    }
}
