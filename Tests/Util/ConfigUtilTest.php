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

use Sonatra\Bundle\MailerBundle\Exception\InvalidConfigurationException;
use Sonatra\Bundle\MailerBundle\Exception\UnexpectedTypeException;
use Sonatra\Bundle\MailerBundle\Util\ConfigUtil;

/**
 * Tests for config util.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ConfigUtilTest extends \PHPUnit_Framework_TestCase
{
    public function testFormatConfigWithString()
    {
        $config = 'filename.file';

        $valid = array(
            'file' => $config,
        );

        $this->assertEquals($valid, ConfigUtil::formatConfig($config));
    }

    public function testFormatConfigWithoutFile()
    {
        $msg = 'Expected argument of type "array", "integer" given';
        $this->setExpectedException(UnexpectedTypeException::class, $msg);

        $config = 42;

        ConfigUtil::formatConfig($config);
    }

    public function testFormatConfigWithInvalidFilename()
    {
        $msg = 'The "file" attribute must be defined in config of layout template';
        $this->setExpectedException(InvalidConfigurationException::class, $msg);

        $config = array(
            'name' => 'test',
        );

        ConfigUtil::formatConfig($config);
    }
}
