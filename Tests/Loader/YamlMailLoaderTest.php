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
use Sonatra\Bundle\MailerBundle\Loader\LayoutLoaderInterface;
use Sonatra\Bundle\MailerBundle\Loader\YamlMailLoader;
use Sonatra\Bundle\MailerBundle\Model\LayoutInterface;
use Sonatra\Bundle\MailerBundle\Model\MailInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Tests for yaml mail loader.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class YamlMailLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testLoad()
    {
        // layout
        $templateLayout = $this->getMock(LayoutInterface::class);
        $templateLayout->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('test'));
        $templateLayout->expects($this->any())
            ->method('isEnabled')
            ->will($this->returnValue(true));

        // loader
        /* @var LayoutLoaderInterface|\PHPUnit_Framework_MockObject_MockObject $layoutLoader */
        $layoutLoader = $this->getMock(LayoutLoaderInterface::class);
        $layoutLoader->expects($this->once())
            ->method('load')
            ->will($this->returnValue($templateLayout));

        /* @var KernelInterface|\PHPUnit_Framework_MockObject_MockObject $kernel */
        $kernel = $this->getMock(KernelInterface::class);
        $template = '@AcmeDemoBundle/Resources/loaders/mail.yml';

        $kernel->expects($this->once())
            ->method('locateResource')
            ->will($this->returnValue(__DIR__.'/../Fixtures/loaders/mail.yml'));

        $loader = new YamlMailLoader(array($template), $layoutLoader, $kernel);

        $this->assertInstanceOf(MailInterface::class, $loader->load('test'));
    }

    public function testLoadUnknownTemplate()
    {
        $this->setExpectedException(UnknownMailException::class, 'The "test" mail template does not exist with the "all" type');
        /* @var LayoutLoaderInterface $layoutLoader */
        $layoutLoader = $this->getMock(LayoutLoaderInterface::class);
        /* @var KernelInterface $kernel */
        $kernel = $this->getMock(KernelInterface::class);

        $loader = new YamlMailLoader(array(), $layoutLoader, $kernel);

        $loader->load('test');
    }
}
