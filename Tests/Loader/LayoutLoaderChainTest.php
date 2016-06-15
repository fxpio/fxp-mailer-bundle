<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Tests\Loader;

use Sonatra\Bundle\MailerBundle\Exception\UnknownLayoutException;
use Sonatra\Bundle\MailerBundle\Loader\LayoutLoaderChain;
use Sonatra\Bundle\MailerBundle\Loader\LayoutLoaderInterface;
use Sonatra\Bundle\MailerBundle\Model\LayoutInterface;

/**
 * Tests for chain mail loader.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class LayoutLoaderChainTest extends \PHPUnit_Framework_TestCase
{
    public function testLoad()
    {
        $template = $this->getMockBuilder(LayoutInterface::class)->getMock();

        $loader1 = $this->getMockBuilder(LayoutLoaderInterface::class)->getMock();
        $loader1->expects($this->once())
            ->method('load')
            ->willThrowException(new UnknownLayoutException('test'));

        $loader2 = $this->getMockBuilder(LayoutLoaderInterface::class)->getMock();
        $loader2->expects($this->once())
            ->method('load')
            ->will($this->returnValue($template));

        $chainLoader = new LayoutLoaderChain(array($loader1, $loader2));

        $this->assertSame($template, $chainLoader->load('test'));
    }

    /**
     * @expectedException \Sonatra\Bundle\MailerBundle\Exception\UnknownLayoutException
     * @expectedExceptionMessage The "test" layout template does not exist
     */
    public function testLoadUnknownTemplate()
    {
        $loader = new LayoutLoaderChain(array());

        $loader->load('test');
    }
}
