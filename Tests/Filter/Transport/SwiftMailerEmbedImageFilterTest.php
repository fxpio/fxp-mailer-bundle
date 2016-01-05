<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Tests\Filter\Transport;

use Sonatra\Bundle\MailerBundle\Filter\Transport\SwiftMailerEmbedImageFilter;
use Sonatra\Bundle\MailerBundle\Mailer\MailRenderedInterface;

/**
 * Tests for swift mailer embed image filter.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class SwiftMailerEmbedImageFilterTest extends \PHPUnit_Framework_TestCase
{
    public function getSupportTests()
    {
        $message = $this->getMockBuilder(\Swift_Message::class)->disableOriginalConstructor()->getMock();
        $mailRendered = $this->getMock(MailRenderedInterface::class);

        return array(
            array('swiftmailer', $message,        $mailRendered,    true),
            array('test',        $message,        $mailRendered,    false),
            array('swiftmailer', new \stdClass(), $mailRendered,    false),
            array('swiftmailer', $message,        null,             false),
            array('swiftmailer', new \stdClass(), null,             false),
            array('test',        new \stdClass(), null,             false),
        );
    }

    /**
     * @dataProvider getSupportTests
     *
     * @param string                     $transport    Transport name
     * @param mixed                      $message      The message
     * @param MailRenderedInterface|null $mailRendered The mail rendered
     * @param bool                       $supported    Check if the transport is supported
     */
    public function testSupports($transport, $message, $mailRendered, $supported)
    {
        $filter = new SwiftMailerEmbedImageFilter();

        $this->assertSame($supported, $filter->supports($transport, $message, $mailRendered));
    }

    public function testFilter()
    {
        $html = '<html><body><img src="test.png"><p>Test.</p></body></html>';
        $htmlConverted = '<html><body><img src="EMBED_CID"><p>Test.</p></body></html>';
        $document = new \DOMDocument('1.0', 'utf-8');
        $document->loadHTML($htmlConverted);
        $htmlConverted = $document->saveHTML();

        /* @var \Swift_Message|\PHPUnit_Framework_MockObject_MockObject $message */
        $message = $this->getMockBuilder(\Swift_Message::class)->disableOriginalConstructor()->getMock();
        /* @var MailRenderedInterface|\PHPUnit_Framework_MockObject_MockObject $mailRendered */
        $mailRendered = $this->getMock(MailRenderedInterface::class);
        $mailRendered->expects($this->atLeastOnce())
            ->method('getHtmlBody')
            ->will($this->returnValue($html));
        $mailRendered->expects($this->once())
            ->method('setHtmlBody')
            ->with($htmlConverted);

        $message->expects($this->once())
            ->method('embed')
            ->will($this->returnValue('EMBED_CID'));

        $filter = new SwiftMailerEmbedImageFilter();
        $filter->filter('swiftmailer', $message, $mailRendered);
    }

    public function testFilterWithInvalidMessage()
    {
        /* @var MailRenderedInterface|\PHPUnit_Framework_MockObject_MockObject $mailRendered */
        $mailRendered = $this->getMock(MailRenderedInterface::class);
        $mailRendered->expects($this->never())
            ->method('setHtmlBody');

        $filter = new SwiftMailerEmbedImageFilter();
        $filter->filter('swiftmailer', null, $mailRendered);
    }

    public function testFilterWithEmptyHtmlBody()
    {
        /* @var \Swift_Message|\PHPUnit_Framework_MockObject_MockObject $message */
        $message = $this->getMockBuilder(\Swift_Message::class)->disableOriginalConstructor()->getMock();
        /* @var MailRenderedInterface|\PHPUnit_Framework_MockObject_MockObject $mailRendered */
        $mailRendered = $this->getMock(MailRenderedInterface::class);
        $mailRendered->expects($this->once())
            ->method('getHtmlBody')
            ->will($this->returnValue(null));
        $mailRendered->expects($this->never())
            ->method('setHtmlBody');

        $filter = new SwiftMailerEmbedImageFilter();
        $filter->filter('swiftmailer', $message, $mailRendered);
    }
}
