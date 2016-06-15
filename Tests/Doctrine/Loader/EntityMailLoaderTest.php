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
use Sonatra\Bundle\MailerBundle\Doctrine\Loader\EntityMailLoader;
use Sonatra\Bundle\MailerBundle\Entity\Mail;
use Sonatra\Bundle\MailerBundle\MailTypes;
use Sonatra\Bundle\MailerBundle\Util\MailUtil;

/**
 * Tests for entity mail loader.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class EntityMailLoaderTest extends \PHPUnit_Framework_TestCase
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
     * @var EntityMailLoader
     */
    protected $loader;

    protected function setUp()
    {
        $class = Mail::class;

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

        $this->loader = new EntityMailLoader($registry, $class);
    }

    public function testLoad()
    {
        $template = $this->getMockBuilder(Mail::class)->disableOriginalConstructor()->getMock();
        $this->repo->expects($this->once())
            ->method('findOneBy')
            ->with(array(
                'name' => 'test',
                'enabled' => true,
                'type' => MailUtil::getValidTypes(MailTypes::TYPE_ALL),
            ))
            ->will($this->returnValue($template))
        ;

        $this->assertSame($template, $this->loader->load('test'));
    }

    /**
     * @expectedException \Sonatra\Bundle\MailerBundle\Exception\UnknownMailException
     * @expectedExceptionMessage The "test" mail template does not exist with the "all" type
     */
    public function testLoadUnknownTemplate()
    {
        $this->loader->load('test');
    }
}
