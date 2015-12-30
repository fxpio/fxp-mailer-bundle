<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Tests\Mailer;

use Sonatra\Bundle\MailerBundle\Exception\InvalidArgumentException;
use Sonatra\Bundle\MailerBundle\Mailer\Mailer;
use Sonatra\Bundle\MailerBundle\Mailer\MailRenderedInterface;
use Sonatra\Bundle\MailerBundle\Mailer\MailTemplaterInterface;
use Sonatra\Bundle\MailerBundle\MailTypes;
use Sonatra\Bundle\MailerBundle\Transport\TransportInterface;

/**
 * Tests for mailer.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class MailerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MailTemplaterInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $templater;

    /**
     * @var TransportInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $transport;

    /**
     * @var Mailer
     */
    protected $mailer;

    protected function setUp()
    {
        $this->templater = $this->getMock(MailTemplaterInterface::class);
        $this->transport = $this->getMock(TransportInterface::class);
        $this->transport->expects($this->atLeastOnce())
            ->method('getName')
            ->will($this->returnValue('test'));

        $this->mailer = new Mailer($this->templater, array($this->transport));
    }

    public function testGetTransport()
    {
        $this->assertTrue($this->mailer->hasTransport('test'));
        $this->assertFalse($this->mailer->hasTransport('foo'));

        $this->assertSame($this->transport, $this->mailer->getTransport('test'));
    }

    public function testGetInvalidTransport()
    {
        $msg = 'The "foo" transport does not exist';
        $this->setExpectedException(InvalidArgumentException::class, $msg);

        $this->mailer->getTransport('foo');
    }

    public function testSend()
    {
        $message = $this->getMockBuilder(\Swift_Message::class)->disableOriginalConstructor()->getMock();
        $mail = $this->getMock(MailRenderedInterface::class);

        $this->templater->expects($this->once())
            ->method('render')
            ->with('template-test', array(), MailTypes::TYPE_ALL)
            ->will($this->returnValue($mail));

        $this->transport->expects($this->once())
            ->method('send')
            ->with($message, $mail)
            ->will($this->returnValue(true));

        $res = $this->mailer->send('test', $message, 'template-test', array(), MailTypes::TYPE_ALL);

        $this->assertTrue($res);
    }

    public function testSendWithoutTemplate()
    {
        $message = $this->getMockBuilder(\Swift_Message::class)->disableOriginalConstructor()->getMock();

        $this->templater->expects($this->never())
            ->method('render');

        $this->transport->expects($this->once())
            ->method('send')
            ->with($message, null)
            ->will($this->returnValue(true));

        $res = $this->mailer->send('test', $message);

        $this->assertTrue($res);
    }
}
