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
use Sonatra\Bundle\MailerBundle\Loader\YamlLayoutLoader;
use Sonatra\Bundle\MailerBundle\Model\LayoutInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Tests for yaml layout loader.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class YamlLayoutLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testLoad()
    {
        /* @var KernelInterface|\PHPUnit_Framework_MockObject_MockObject $kernel */
        $kernel = $this->getMock(KernelInterface::class);
        $template = '@AcmeDemoBundle/Resources/loaders/layout.yml';

        $kernel->expects($this->once())
            ->method('locateResource')
            ->will($this->returnValue(__DIR__.'/../Fixtures/loaders/layout.yml'));

        $loader = new YamlLayoutLoader(array($template), $kernel);

        $this->assertInstanceOf(LayoutInterface::class, $loader->load('test'));
    }

    public function testLoadUnknownTemplate()
    {
        $this->setExpectedException(UnknownLayoutException::class, 'The "test" layout template does not exist');

        /* @var KernelInterface $kernel */
        $kernel = $this->getMock(KernelInterface::class);

        $loader = new YamlLayoutLoader(array(), $kernel);

        $loader->load('test');
    }
}
