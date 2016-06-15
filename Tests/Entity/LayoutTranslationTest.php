<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Tests\Entity;

use Sonatra\Bundle\MailerBundle\Entity\LayoutTranslation;
use Sonatra\Bundle\MailerBundle\Model\LayoutInterface;

/**
 * Tests for layout translation template entity.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class LayoutTranslationTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {
        /* @var LayoutInterface $layout */
        $layout = $this->getMockBuilder(LayoutInterface::class)->getMock();
        $translation = new LayoutTranslation($layout);

        $this->assertNull($translation->getId());
    }
}
