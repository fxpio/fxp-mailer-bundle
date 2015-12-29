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

use Sonatra\Bundle\MailerBundle\Entity\MailTranslation;
use Sonatra\Bundle\MailerBundle\Model\MailInterface;

/**
 * Tests for mail translation template entity.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class MailTranslationTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {
        /* @var MailInterface $mail */
        $mail = $this->getMock(MailInterface::class);
        $translation = new MailTranslation($mail);

        $this->assertNull($translation->getId());
    }
}
