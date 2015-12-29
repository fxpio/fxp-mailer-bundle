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

use Sonatra\Bundle\MailerBundle\Model\Layout;
use Sonatra\Bundle\MailerBundle\Model\LayoutInterface;
use Sonatra\Bundle\MailerBundle\Model\LayoutTranslation;
use Sonatra\Bundle\MailerBundle\Model\LayoutTranslationInterface;

/**
 * Tests for layout template model.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class LayoutTest extends \PHPUnit_Framework_TestCase
{
    public function testModel()
    {
        /* @var LayoutTranslationInterface $translation */
        $translation = $this->getMock(LayoutTranslationInterface::class);

        $layout = new Layout();
        $layout->setTranslationDomain('domain');

        $this->assertTrue(is_array($layout->getMails()));
        $this->assertTrue(is_array($layout->getTranslations()));

        $this->assertCount(0, $layout->getTranslations());
        $layout->addTranslation($translation);
        $this->assertCount(1, $layout->getTranslations());
        $layout->removeTranslation($translation);
        $this->assertCount(0, $layout->getTranslations());
        $this->assertSame('domain', $layout->getTranslationDomain());
    }

    public function testGetTranslation()
    {
        /* @var Layout $layout */
        /* @var LayoutTranslation $translation */
        list($layout, $translation) = $this->getModels('fr_fr');

        $translated = $layout->getTranslation('fr_fr');

        $this->assertInstanceOf(LayoutInterface::class, $translated);
        $this->assertNotSame($translation, $translated);
        $this->assertSame($translation->getLabel(), $translated->getLabel());
        $this->assertSame($translation->getDescription(), $translated->getDescription());
        $this->assertSame($translation->getBody(), $translated->getBody());

        $this->assertSame($translated, $layout->getTranslation('fr_fr'));
    }

    public function testGetFallbackTranslation()
    {
        /* @var Layout $layout */
        /* @var LayoutTranslation $translation */
        list($layout, $translation) = $this->getModels('fr');

        $translated = $layout->getTranslation('fr_fr');

        $this->assertInstanceOf(LayoutInterface::class, $translated);
        $this->assertNotSame($translation, $translated);
        $this->assertSame($translation->getLabel(), $translated->getLabel());
        $this->assertSame($translation->getDescription(), $translated->getDescription());
        $this->assertSame($translation->getBody(), $translated->getBody());

        $this->assertSame($translated, $layout->getTranslation('fr_fr'));
    }

    public function testGetNotTranslation()
    {
        /* @var Layout $layout */
        /* @var LayoutTranslation $translation */
        list($layout, $translation) = $this->getModels('fr_fr');

        $translated = $layout->getTranslation('fr');

        $this->assertInstanceOf(LayoutInterface::class, $translated);
        $this->assertNotSame($translation, $translated);
        $this->assertNotSame($translation->getLabel(), $translated->getLabel());
        $this->assertNotSame($translation->getDescription(), $translated->getDescription());
        $this->assertNotSame($translation->getBody(), $translated->getBody());

        $this->assertSame($translated, $layout->getTranslation('fr'));
    }

    /**
     * Get the layout and translation models.
     *
     * @param string $locale The locale
     *
     * @return array The layout and translation
     */
    protected function getModels($locale)
    {
        $layout = new Layout();
        $layout
            ->setName('test')
            ->setLabel('Label of template')
            ->setDescription('Description of template')
            ->setBody('Body of template')
        ;

        $translation = new LayoutTranslation($layout);
        $translation
            ->setLocale($locale)
            ->setLabel('Label of translated template')
            ->setDescription('Description of translated template')
            ->setBody('Body of translated template')
        ;

        return array($layout, $translation);
    }
}
