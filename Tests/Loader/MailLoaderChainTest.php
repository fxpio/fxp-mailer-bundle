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

use Sonatra\Bundle\MailerBundle\Exception\UnknownMailException;
use Sonatra\Bundle\MailerBundle\Loader\MailLoaderChain;
use Sonatra\Bundle\MailerBundle\Loader\MailLoaderInterface;
use Sonatra\Bundle\MailerBundle\MailTypes;
use Sonatra\Bundle\MailerBundle\Model\MailInterface;

/**
 * Tests for chain layout loader.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class MailLoaderChainTest extends \PHPUnit_Framework_TestCase
{
    public function testLoad()
    {
        $template = $this->getMock(MailInterface::class);

        $loader1 = $this->getMock(MailLoaderInterface::class);
        $loader1->expects($this->once())
            ->method('load')
            ->willThrowException(new UnknownMailException('test', MailTypes::TYPE_ALL));

        $loader2 = $this->getMock(MailLoaderInterface::class);
        $loader2->expects($this->once())
            ->method('load')
            ->will($this->returnValue($template));

        $chainLoader = new MailLoaderChain(array($loader1, $loader2));

        $this->assertSame($template, $chainLoader->load('test'));
    }

    public function testLoadUnknownTemplate()
    {
        $this->setExpectedException(UnknownMailException::class, 'The "test" mail template does not exist with the "all" type');

        $loader = new MailLoaderChain(array());

        $loader->load('test');
    }
}
