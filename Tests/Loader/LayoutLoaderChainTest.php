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
        $template = $this->getMock(LayoutInterface::class);

        $loader1 = $this->getMock(LayoutLoaderInterface::class);
        $loader1->expects($this->once())
            ->method('load')
            ->willThrowException(new UnknownLayoutException('test'));

        $loader2 = $this->getMock(LayoutLoaderInterface::class);
        $loader2->expects($this->once())
            ->method('load')
            ->will($this->returnValue($template));

        $chainLoader = new LayoutLoaderChain(array($loader1, $loader2));

        $this->assertSame($template, $chainLoader->load('test'));
    }

    public function testLoadUnknownTemplate()
    {
        $this->setExpectedException(UnknownLayoutException::class, 'The "test" layout template does not exist');

        $loader = new LayoutLoaderChain(array());

        $loader->load('test');
    }
}
