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

use Sonatra\Bundle\MailerBundle\Model\MailInterface;
use Sonatra\Bundle\MailerBundle\Model\MailTranslation;

/**
 * Tests for mail translation template model.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class MailTranslationTest extends \PHPUnit_Framework_TestCase
{
    public function testModel()
    {
        /* @var MailInterface $mail */
        $mail = $this->getMock(MailInterface::class);
        $translation = new MailTranslation($mail);
        $translation
            ->setSubject('Subject of translation')
            ->setHtmlBody('HTML body of translation')
            ->setLocale('fr')
            ->setLabel('Label of translation')
            ->setDescription('Description of translation')
            ->setBody('Body of translation')
        ;

        $this->assertSame($mail, $translation->getMail());
        $this->assertSame('fr', $translation->getLocale());
        $this->assertSame('Label of translation', $translation->getLabel());
        $this->assertSame('Description of translation', $translation->getDescription());
        $this->assertSame('Subject of translation', $translation->getSubject());
        $this->assertSame('HTML body of translation', $translation->getHtmlBody());
        $this->assertSame('Body of translation', $translation->getBody());
    }
}
