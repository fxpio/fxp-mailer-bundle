<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Tests\Util;

use Sonatra\Bundle\MailerBundle\Util\EmbedImageUtil;

/**
 * Tests for util of swiftmailer embed image.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class EmbedImageUtilTest extends \PHPUnit_Framework_TestCase
{
    public function getLocalePathData()
    {
        $webDir = __DIR__.'/../Fixtures';

        return array(
            array('http://www.example.tld/loaders/mail.yml', $webDir, '/(.*)+/', realpath($webDir.'/loaders/mail.yml')),
            array('http://www.example.tld/loaders/mail.yml', $webDir, '/(.*)+.example.tld$/', realpath($webDir.'/loaders/mail.yml')),
            array('http://www.example.tld/loaders/mail.yml', $webDir, '/^example.tld$/', 'http://www.example.tld/loaders/mail.yml'),
            array('./loaders/mail.yml', $webDir, '/(.*)+.example.tld$/', realpath($webDir.'/loaders/mail.yml')),
            array('loaders/mail.yml', $webDir, '/(.*)+.example.tld$/', realpath($webDir.'/loaders/mail.yml')),
            array('/loaders/mail.yml', $webDir, '/(.*)+.example.tld$/', realpath($webDir.'/loaders/mail.yml')),
            array('http://www.example.tld/loaders/mail.yml', $webDir, '/^((.*)+\.)?example.tld$/', realpath($webDir.'/loaders/mail.yml')),
            array('http://example.tld/loaders/mail.yml', $webDir, '/^((.*)+\.)?example.tld$/', realpath($webDir.'/loaders/mail.yml')),
        );
    }

    /**
     * @dataProvider getLocalePathData
     *
     * @param string $path
     * @param string $webDir
     * @param string $hostPattern
     * @param string $valid
     */
    public function testGetLocalPath($path, $webDir, $hostPattern, $valid)
    {
        $this->assertSame($valid, EmbedImageUtil::getLocalPath($path, $webDir, $hostPattern));
    }
}
