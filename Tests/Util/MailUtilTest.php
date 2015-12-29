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

use Sonatra\Bundle\MailerBundle\MailTypes;
use Sonatra\Bundle\MailerBundle\Model\MailInterface;
use Sonatra\Bundle\MailerBundle\Util\MailUtil;

/**
 * Tests for util mail.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class MailUtilTest extends \PHPUnit_Framework_TestCase
{
    public function getCheckIsValidMailTypes()
    {
        return array(
            array(true,  MailTypes::TYPE_ALL,    true,  MailTypes::TYPE_ALL),
            array(true,  MailTypes::TYPE_ALL,    true,  MailTypes::TYPE_PRINT),
            array(true,  MailTypes::TYPE_ALL,    true,  MailTypes::TYPE_SCREEN),
            array(true,  MailTypes::TYPE_PRINT,  true,  MailTypes::TYPE_ALL),
            array(true,  MailTypes::TYPE_PRINT,  true,  MailTypes::TYPE_PRINT),
            array(false, MailTypes::TYPE_PRINT,  true,  MailTypes::TYPE_SCREEN),
            array(true,  MailTypes::TYPE_SCREEN, true,  MailTypes::TYPE_ALL),
            array(false, MailTypes::TYPE_SCREEN, true,  MailTypes::TYPE_PRINT),
            array(true,  MailTypes::TYPE_SCREEN, true,  MailTypes::TYPE_SCREEN),

            array(false, MailTypes::TYPE_ALL,    false, MailTypes::TYPE_ALL),
            array(false, MailTypes::TYPE_ALL,    false, MailTypes::TYPE_PRINT),
            array(false, MailTypes::TYPE_ALL,    false, MailTypes::TYPE_SCREEN),
            array(false, MailTypes::TYPE_PRINT,  false, MailTypes::TYPE_ALL),
            array(false, MailTypes::TYPE_PRINT,  false, MailTypes::TYPE_PRINT),
            array(false, MailTypes::TYPE_PRINT,  false, MailTypes::TYPE_SCREEN),
            array(false, MailTypes::TYPE_SCREEN, false, MailTypes::TYPE_ALL),
            array(false, MailTypes::TYPE_SCREEN, false, MailTypes::TYPE_PRINT),
            array(false, MailTypes::TYPE_SCREEN, false, MailTypes::TYPE_SCREEN),
        );
    }

    /**
     * @dataProvider getCheckIsValidMailTypes
     *
     * @param bool   $result      The result value
     * @param string $entryType   The entry type
     * @param bool   $mailEnabled The value of mail field 'enabled'
     * @param string $mailType    The value of mail field 'type'
     */
    public function testIsValid($result, $entryType, $mailEnabled, $mailType)
    {
        /* @var MailInterface|\PHPUnit_Framework_MockObject_MockObject $mail */
        $mail = $this->getMock(MailInterface::class);
        $mail->expects($this->any())
            ->method('isEnabled')
            ->will($this->returnValue($mailEnabled));
        $mail->expects($this->any())
            ->method('getType')
            ->will($this->returnValue($mailType));

        $this->assertSame($result, MailUtil::isValid($mail, $entryType));
    }

    public function getTypes()
    {
        return array(
            array(MailTypes::TYPE_ALL,    array(MailTypes::TYPE_ALL, MailTypes::TYPE_PRINT, MailTypes::TYPE_SCREEN)),
            array(MailTypes::TYPE_PRINT,  array(MailTypes::TYPE_ALL, MailTypes::TYPE_PRINT)),
            array(MailTypes::TYPE_SCREEN, array(MailTypes::TYPE_ALL, MailTypes::TYPE_SCREEN)),
            array('invalid',              array(MailTypes::TYPE_ALL, MailTypes::TYPE_PRINT, MailTypes::TYPE_SCREEN)),
        );
    }

    /**
     * @dataProvider getTypes
     *
     * @param string   $type       The entry type
     * @param string[] $validTypes The valid types for entry
     */
    public function testGetValidTypes($type, $validTypes)
    {
        $this->assertEquals($validTypes, MailUtil::getValidTypes($type));
    }
}
