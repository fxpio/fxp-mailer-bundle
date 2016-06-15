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

use Sonatra\Bundle\MailerBundle\Exception\InvalidArgumentException;
use Sonatra\Bundle\MailerBundle\Model\TwigMail;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Tests for twig mail template model.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class TwigMailTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    protected $file;

    protected function setUp()
    {
        $this->file = sys_get_temp_dir().'/sonatra_mailer_tests/file.html.twig';
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
        $mail = new TwigMail($this->file);

        $this->assertSame($this->file, $mail->getFile());
    }

    /**
     * @expectedException \Sonatra\Bundle\MailerBundle\Exception\InvalidArgumentException
     * @expectedExceptionMessage The "file.ext" file is not supported by the mail file template
     */
    public function testInvalidFile()
    {
        new TwigMail('file.ext');
    }
}
