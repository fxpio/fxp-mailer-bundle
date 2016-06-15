<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Tests\Transport;

use Sonatra\Bundle\MailerBundle\Mailer\MailRenderedInterface;
use Sonatra\Bundle\MailerBundle\Transport\SwiftMailerTransport;

/**
 * Tests for swift mailer transport.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class SwiftMailerTransportTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Swift_Mailer|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $swiftMailer;

    /**
     * @var SwiftMailerTransport
     */
    protected $transport;

    protected function setUp()
    {
        $this->swiftMailer = $this->getMockBuilder(\Swift_Mailer::class)->disableOriginalConstructor()->getMock();
        $this->transport = new SwiftMailerTransport($this->swiftMailer);
    }

    public function testGetName()
    {
        $this->assertSame('swiftmailer', $this->transport->getName());
    }

    /**
     * @expectedException \Sonatra\Bundle\MailerBundle\Exception\UnexpectedTypeException
     * @expectedExceptionMessage Expected argument of type "Swift_Message", "integer" given
     */
    public function testInvalidType()
    {
        $this->transport->validate(42);
    }

    public function testSend()
    {
        $message = $this->getMockBuilder(\Swift_Message::class)->disableOriginalConstructor()->getMock();

        $this->swiftMailer->expects($this->at(0))
            ->method('send')
            ->with($message)
            ->will($this->returnValue(1));

        $this->assertTrue($this->transport->send($message));
    }

    public function getHtmlValues()
    {
        return array(
            array('HTML Body'),
            array(null),
        );
    }

    /**
     * @dataProvider getHtmlValues
     *
     * @param string|null $htmlValue
     */
    public function testSendWithMailRendered($htmlValue)
    {
        $message = $this->getMockBuilder(\Swift_Message::class)->disableOriginalConstructor()->getMock();
        /* @var MailRenderedInterface|\PHPUnit_Framework_MockObject_MockObject $mail */
        $mail = $this->getMockBuilder(MailRenderedInterface::class)->getMock();
        $mail->expects($this->once())
            ->method('getSubject')
            ->will($this->returnValue('Subject'));

        $mail->expects($this->once())
            ->method('getHTMLBody')
            ->will($this->returnValue($htmlValue));

        $mail->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue('Body'));

        $this->swiftMailer->expects($this->at(0))
            ->method('send')
            ->with($message)
            ->will($this->returnValue(1));

        $this->assertTrue($this->transport->send($message, $mail));
    }
}
