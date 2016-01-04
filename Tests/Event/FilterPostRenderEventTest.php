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

use Sonatra\Bundle\MailerBundle\Event\FilterPostRenderEvent;
use Sonatra\Bundle\MailerBundle\Mailer\MailRenderedInterface;

/**
 * Tests for filter post render event.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class FilterPostRenderEventTest extends \PHPUnit_Framework_TestCase
{
    public function testModel()
    {
        /* @var MailRenderedInterface $mailRendered */
        $mailRendered = $this->getMock(MailRenderedInterface::class);
        $event = new FilterPostRenderEvent($mailRendered);

        $this->assertSame($mailRendered, $event->getMailRendered());
    }
}
