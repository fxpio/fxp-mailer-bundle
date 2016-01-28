<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Tests\Transport\SwiftMailer;

use Sonatra\Bundle\MailerBundle\Transport\SwiftMailer\EmbedImagePlugin;

/**
 * Tests for swift mailer embed image plugin.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class EmbedImagePluginTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Swift_Message|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $message;

    /**
     * @var \Swift_Events_SendEvent|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $event;

    /**
     * @var EmbedImagePlugin
     */
    protected $plugin;

    protected function setUp()
    {
        $this->message = $this->getMockBuilder(\Swift_Message::class)
            ->disableOriginalConstructor()->getMock();

        $this->event = $this->getMockBuilder(\Swift_Events_SendEvent::class)
            ->disableOriginalConstructor()->getMock();

        $this->event->expects($this->any())
            ->method('getMessage')
            ->will($this->returnValue($this->message));

        $this->plugin = new EmbedImagePlugin();
    }

    public function testBeforeSendPerformed()
    {
        $messageId = 'message_id';
        $html = '<html><body><img src="test.png"><p>Test.</p><img src="test.png"></body></html>';
        $htmlConverted = '<html><body><img src="cid:EMBED_CID"><p>Test.</p><img src="cid:EMBED_CID"></body></html>';
        $document = new \DOMDocument('1.0', 'utf-8');
        $document->loadHTML($htmlConverted);
        $htmlConverted = $document->saveHTML();

        $this->message->expects($this->atLeastOnce())
            ->method('getBody')
            ->will($this->returnValue($html));

        $this->message->expects($this->atLeastOnce())
            ->method('getId')
            ->will($this->returnValue($messageId));

        $this->message->expects($this->once())
            ->method('embed')
            ->will($this->returnValue('cid:EMBED_CID'));

        $this->message->expects($this->once())
            ->method('setBody')
            ->with($htmlConverted);

        $this->plugin->beforeSendPerformed($this->event);
    }

    public function testBeforeSendPerformedWithAlreadyEmbeddedImage()
    {
        $messageId = 'message_id';
        $html = '<html><body><img src="cid:ALREADY_EMBED_CID"><p>Test.</p><img src="test.png"></body></html>';
        $htmlConverted = '<html><body><img src="cid:ALREADY_EMBED_CID"><p>Test.</p><img src="cid:EMBED_CID"></body></html>';
        $document = new \DOMDocument('1.0', 'utf-8');
        $document->loadHTML($htmlConverted);
        $htmlConverted = $document->saveHTML();

        $this->message->expects($this->atLeastOnce())
            ->method('getBody')
            ->will($this->returnValue($html));

        $this->message->expects($this->atLeastOnce())
            ->method('getId')
            ->will($this->returnValue($messageId));

        $this->message->expects($this->once())
            ->method('embed')
            ->will($this->returnValue('cid:EMBED_CID'));

        $this->message->expects($this->once())
            ->method('setBody')
            ->with($htmlConverted);

        $this->plugin->beforeSendPerformed($this->event);
    }

    public function testBeforeSendPerformedWithInvalidMessage()
    {
        $this->event = $this->getMockBuilder(\Swift_Events_SendEvent::class)
            ->disableOriginalConstructor()->getMock();

        $this->event->expects($this->any())
            ->method('getMessage')
            ->will($this->returnValue(new \stdClass()));

        $this->message->expects($this->never())
            ->method('embed');

        $this->message->expects($this->never())
            ->method('setBody');

        $this->plugin->beforeSendPerformed($this->event);
    }

    public function testBeforeSendPerformedWithEmptyBody()
    {
        $this->message->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue(null));

        $this->message->expects($this->never())
            ->method('embed');

        $this->message->expects($this->never())
            ->method('setBody');

        $this->plugin->beforeSendPerformed($this->event);
    }

    public function testBeforeSendPerformedWithDisabledPlugin()
    {
        $this->plugin->setEnabled(false);

        $this->message->expects($this->never())
            ->method('embed');

        $this->message->expects($this->never())
            ->method('setBody');

        $this->plugin->beforeSendPerformed($this->event);
    }

    public function testSendPerformed()
    {
        $this->plugin->sendPerformed($this->event);
    }
}
