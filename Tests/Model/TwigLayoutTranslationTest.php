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
use Sonatra\Bundle\MailerBundle\Model\TwigLayoutTranslation;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Tests for twig layout translation template model.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class TwigLayoutTranslationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    protected $file;

    /**
     * @var LayoutInterface
     */
    protected $layout;

    protected function setUp()
    {
        $this->file = sys_get_temp_dir().'/sonatra_mailer_tests/file.html.twig';
        $this->layout = $this->getMockBuilder(LayoutInterface::class)->getMock();
        $fs = new Filesystem();
        $fs->dumpFile($this->file, 'content');
    }

    protected function tearDown()
    {
        $fs = new Filesystem();
        $fs->remove(dirname($this->file));
    }

    public function testModel()
    {
        $layout = new TwigLayoutTranslation($this->layout, $this->file);

        $this->assertSame($this->file, $layout->getFile());
    }

    /**
     * @expectedException \Sonatra\Bundle\MailerBundle\Exception\InvalidArgumentException
     * @expectedExceptionMessage The "file.ext" file is not supported by the layout translation file template
     */
    public function testInvalidFile()
    {
        new TwigLayoutTranslation($this->layout, 'file.ext');
    }
}
