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

use Sonatra\Bundle\MailerBundle\Event\FilterPostSendEvent;
use Sonatra\Bundle\MailerBundle\Mailer\MailRenderedInterface;

/**
 * Tests for filter post send event.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class FilterPostSendEventTest extends \PHPUnit_Framework_TestCase
{
    public function testModel()
    {
        $result = true;
        $transport = 'transport_name';
        $message = new \stdClass();
        /* @var MailRenderedInterface $mailRendered */
        $mailRendered = $this->getMockBuilder(MailRenderedInterface::class)->getMock();

        $event = new FilterPostSendEvent($result, $transport, $message, $mailRendered);

        $this->assertSame($result, $event->isSend());
        $this->assertSame($transport, $event->getTransport());
        $this->assertSame($message, $event->getMessage());
        $this->assertSame($mailRendered, $event->getMailRendered());
    }
}
