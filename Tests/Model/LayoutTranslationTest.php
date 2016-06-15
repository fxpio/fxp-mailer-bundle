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

use Sonatra\Bundle\MailerBundle\Model\LayoutInterface;
use Sonatra\Bundle\MailerBundle\Model\LayoutTranslation;

/**
 * Tests for layout translation template model.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class LayoutTranslationTest extends \PHPUnit_Framework_TestCase
{
    public function testModel()
    {
        /* @var LayoutInterface $layout */
        $layout = $this->getMockBuilder(LayoutInterface::class)->getMock();
        $translation = new LayoutTranslation($layout);
        $translation
            ->setLocale('fr')
            ->setLabel('Label of translation')
            ->setDescription('Description of translation')
            ->setBody('Body of translation')
        ;

        $this->assertSame($layout, $translation->getLayout());
        $this->assertSame('fr', $translation->getLocale());
        $this->assertSame('Label of translation', $translation->getLabel());
        $this->assertSame('Description of translation', $translation->getDescription());
        $this->assertSame('Body of translation', $translation->getBody());
    }
}
