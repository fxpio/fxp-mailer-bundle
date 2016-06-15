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

use Sonatra\Bundle\MailerBundle\Loader\ArrayMailLoader;
use Sonatra\Bundle\MailerBundle\MailTypes;
use Sonatra\Bundle\MailerBundle\Model\MailInterface;

/**
 * Tests for Array mail loader.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ArrayMailLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testLoad()
    {
        $template = $this->getMockBuilder(MailInterface::class)->getMock();
        $template->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('test'));
        $template->expects($this->any())
            ->method('isEnabled')
            ->will($this->returnValue(true));
        $template->expects($this->any())
            ->method('getType')
            ->will($this->returnValue(MailTypes::TYPE_ALL));

        $loader = new ArrayMailLoader(array($template));

        $this->assertSame($template, $loader->load('test'));
    }

    /**
     * @expectedException \Sonatra\Bundle\MailerBundle\Exception\UnknownMailException
     * @expectedExceptionMessage The "test" mail template does not exist with the "all" type
     */
    public function testLoadUnknownTemplate()
    {
        $loader = new ArrayMailLoader(array());

        $loader->load('test');
    }
}
