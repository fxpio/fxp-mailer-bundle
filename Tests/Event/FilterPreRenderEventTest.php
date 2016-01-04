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

use Sonatra\Bundle\MailerBundle\Event\FilterPreRenderEvent;
use Sonatra\Bundle\MailerBundle\MailTypes;

/**
 * Tests for filter pre render event.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class FilterPreRenderEventTest extends \PHPUnit_Framework_TestCase
{
    public function testModel()
    {
        $template = 'template_name';
        $variables = array('foo' => 'bar');
        $type = MailTypes::TYPE_ALL;

        $event = new FilterPreRenderEvent($template, $variables, $type);

        $this->assertSame($template, $event->getTemplate());
        $this->assertSame($variables, $event->getVariables());
        $this->assertSame($type, $event->getType());

        $template2 = 'new_template_name';
        $variables2 = array_merge($variables, array('bar' => 'foo'));
        $type2 = MailTypes::TYPE_SCREEN;

        $event->setTemplate($template2);
        $event->setVariables($variables2);
        $event->setType($type2);

        $this->assertSame($template2, $event->getTemplate());
        $this->assertSame($variables2, $event->getVariables());
        $this->assertSame($type2, $event->getType());
    }
}
