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
        $template = $this->getMock(MailInterface::class);
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

    public function testLoadUnknownTemplate()
    {
        $this->setExpectedException(UnknownMailException::class, 'The "test" mail template does not exist with the "all" type');

        $loader = new ArrayMailLoader(array());

        $loader->load('test');
    }
}
