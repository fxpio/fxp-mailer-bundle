<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Tests\Filter;

use Sonatra\Bundle\MailerBundle\Event\FilterPostRenderEvent;
use Sonatra\Bundle\MailerBundle\Event\FilterPreSendEvent;
use Sonatra\Bundle\MailerBundle\Filter\FilterRegistryInterface;
use Sonatra\Bundle\MailerBundle\Filter\TemplateFilterInterface;
use Sonatra\Bundle\MailerBundle\Filter\TransportFilterInterface;
use Sonatra\Bundle\MailerBundle\Listener\FilterListener;
use Sonatra\Bundle\MailerBundle\Mailer\MailRenderedInterface;
use Sonatra\Bundle\MailerBundle\MailerEvents;

/**
 * Tests for filter listener.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class FilterListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FilterRegistryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $registry;

    /**
     * @var FilterListener
     */
    protected $listener;

    protected function setUp()
    {
        $this->registry = $this->getMock(FilterRegistryInterface::class);
        $this->listener = new FilterListener($this->registry);
    }

    public function testGetSubscribedEvents()
    {
        $events = $this->listener->getSubscribedEvents();
        $valid = array(
            MailerEvents::TEMPLATE_POST_RENDER,
            MailerEvents::TRANSPORT_PRE_SEND,
        );

        $this->assertSame($valid, array_keys($events));
    }

    public function testTemplateFilters()
    {
        /* @var MailRenderedInterface|\PHPUnit_Framework_MockObject_MockObject $mailRendered */
        $mailRendered = $this->getMock(MailRenderedInterface::class);

        /* @var FilterPostRenderEvent|\PHPUnit_Framework_MockObject_MockObject $event */
        $event = $this->getMockBuilder(FilterPostRenderEvent::class)->disableOriginalConstructor()->getMock();
        $event->expects($this->once())
            ->method('getMailRendered')
            ->will($this->returnValue($mailRendered));

        /* @var TemplateFilterInterface|\PHPUnit_Framework_MockObject_MockObject $templateFilter */
        $templateFilter = $this->getMock(TemplateFilterInterface::class);
        $templateFilter->expects($this->once())
            ->method('supports')
            ->with($mailRendered)
            ->will($this->returnValue(true));

        $templateFilter->expects($this->once())
            ->method('filter')
            ->with($mailRendered);

        $this->registry->expects($this->once())
            ->method('getTemplateFilters')
            ->will($this->returnValue(array($templateFilter)));

        $this->listener->onPostRender($event);
    }

    public function testTransportFilters()
    {
        $transport = 'transport_test';
        $message = new \stdClass();

        /* @var MailRenderedInterface|\PHPUnit_Framework_MockObject_MockObject $mailRendered */
        $mailRendered = $this->getMock(MailRenderedInterface::class);

        /* @var FilterPreSendEvent|\PHPUnit_Framework_MockObject_MockObject $event */
        $event = $this->getMockBuilder(FilterPreSendEvent::class)->disableOriginalConstructor()->getMock();
        $event->expects($this->atLeastOnce())
            ->method('getTransport')
            ->will($this->returnValue($transport));
        $event->expects($this->atLeastOnce())
            ->method('getMessage')
            ->will($this->returnValue($message));
        $event->expects($this->atLeastOnce())
            ->method('getMailRendered')
            ->will($this->returnValue($mailRendered));

        /* @var TransportFilterInterface|\PHPUnit_Framework_MockObject_MockObject $transportFilter */
        $transportFilter = $this->getMock(TransportFilterInterface::class);
        $transportFilter->expects($this->once())
            ->method('supports')
            ->with($transport, $message, $mailRendered)
            ->will($this->returnValue(true));

        $transportFilter->expects($this->once())
            ->method('filter')
            ->with($transport, $message, $mailRendered);

        $this->registry->expects($this->once())
            ->method('getTransportFilters')
            ->will($this->returnValue(array($transportFilter)));

        $this->listener->onPreSend($event);
    }
}
