<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Tests\Model;

use Sonatra\Bundle\MailerBundle\Model\AbstractTemplate;
use Sonatra\Bundle\MailerBundle\Model\TemplateInterface;

/**
 * Tests for abstract template model.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class AbstractTemplateTest extends \PHPUnit_Framework_TestCase
{
    public function testModel()
    {
        /* @var TemplateInterface $template */
        $template = $this->getMockForAbstractClass(AbstractTemplate::class);
        $template
            ->setName('test')
            ->setLabel('Test')
            ->setDescription('Description of template')
            ->setEnabled(true)
            ->setBody('Body of template')
        ;

        $this->assertSame('test', $template->getName());
        $this->assertSame('Test', $template->getLabel());
        $this->assertSame('Description of template', $template->getDescription());
        $this->assertTrue($template->isEnabled());
        $this->assertSame('Body of template', $template->getBody());
    }
}
