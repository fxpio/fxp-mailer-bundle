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
use Sonatra\Bundle\MailerBundle\Loader\TwigLayoutLoader;
use Sonatra\Bundle\MailerBundle\Model\LayoutInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Tests for twig layout loader.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class TwigLayoutLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testLoad()
    {
        /* @var KernelInterface|\PHPUnit_Framework_MockObject_MockObject $kernel */
        $kernel = $this->getMock(KernelInterface::class);
        $template = array(
            'name' => 'test',
            'file' => '@AcmeDemoBundle/Resources/loaders/layout.html.twig',
            'translations' => array(
                array(
                    'locale' => 'fr',
                    'file' => '@AcmeDemoBundle/Resources/loaders/layout.fr.html.twig',
                ),
            ),
        );

        $kernel->expects($this->at(0))
            ->method('locateResource')
            ->with('@AcmeDemoBundle/Resources/loaders/layout.html.twig')
            ->will($this->returnValue(__DIR__.'/../Fixtures/loaders/layout.html.twig'));

        $kernel->expects($this->at(1))
            ->method('locateResource')
            ->with('@AcmeDemoBundle/Resources/loaders/layout.fr.html.twig')
            ->will($this->returnValue(__DIR__.'/../Fixtures/loaders/layout.fr.html.twig'));

        $loader = new TwigLayoutLoader(array($template), $kernel);

        $this->assertInstanceOf(LayoutInterface::class, $loader->load('test'));
    }

    public function testLoadUnknownTemplate()
    {
        $this->setExpectedException(UnknownLayoutException::class, 'The "test" layout template does not exist');

        /* @var KernelInterface $kernel */
        $kernel = $this->getMock(KernelInterface::class);

        $loader = new TwigLayoutLoader(array(), $kernel);

        $loader->load('test');
    }
}
