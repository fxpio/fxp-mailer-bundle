<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Tests\Doctrine\Loader;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Sonatra\Bundle\MailerBundle\Doctrine\Loader\EntityLayoutLoader;
use Sonatra\Bundle\MailerBundle\Entity\Layout;

/**
 * Tests for entity layout loader.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class EntityLayoutLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectManager|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $om;

    /**
     * @var ObjectRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $repo;

    /**
     * @var EntityLayoutLoader
     */
    protected $loader;

    protected function setUp()
    {
        $class = Layout::class;

        $this->repo = $this->getMockBuilder(ObjectRepository::class)->getMock();

        $this->om = $this->getMockBuilder(ObjectManager::class)->getMock();
        $this->om->expects($this->once())
            ->method('getRepository')
            ->with($class)
            ->will($this->returnValue($this->repo))
        ;

        /* @var ManagerRegistry|\PHPUnit_Framework_MockObject_MockObject $registry */
        $registry = $this->getMockBuilder(ManagerRegistry::class)->getMock();
        $registry->expects($this->once())
            ->method('getManagerForClass')
            ->with($class)
            ->will($this->returnValue($this->om))
        ;

        $this->loader = new EntityLayoutLoader($registry, $class);
    }

    public function testLoad()
    {
        $template = $this->getMockBuilder(Layout::class)->disableOriginalConstructor()->getMock();
        $this->repo->expects($this->once())
            ->method('findOneBy')
            ->with(array(
                'name' => 'test',
                'enabled' => true,
            ))
            ->will($this->returnValue($template))
        ;

        $this->assertSame($template, $this->loader->load('test'));
    }

    /**
     * @expectedException \Sonatra\Bundle\MailerBundle\Exception\UnknownLayoutException
     * @expectedExceptionMessage The "test" layout template does not exist
     */
    public function testLoadUnknownTemplate()
    {
        $this->loader->load('test');
    }
}
