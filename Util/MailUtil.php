<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Util;

use Sonatra\Bundle\MailerBundle\MailTypes;
use Sonatra\Bundle\MailerBundle\Model\MailInterface;

/**
 * Utils for mail.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
abstract class MailUtil
{
    /**
     * Check if the mail template is valid.
     *
     * @param MailInterface $mail The mail template
     * @param string        $type The mail type defined in MailTypes::TYPE_*
     *
     * @return bool
     */
    public static function isValid(MailInterface $mail, $type)
    {
        $validTypes = static::getValidTypes($type);

        return $mail->isEnabled() && in_array($mail->getType(), $validTypes);
    }

    /**
     * Get the valid mail types.
     *
     * @param string $type The mail type defined in MailTypes::TYPE_*
     *
     * @return string[]
     */
    public static function getValidTypes($type)
    {
        if ($type === MailTypes::TYPE_PRINT) {
            return array(MailTypes::TYPE_ALL, MailTypes::TYPE_PRINT);
        } elseif ($type === MailTypes::TYPE_SCREEN) {
            return array(MailTypes::TYPE_ALL, MailTypes::TYPE_SCREEN);
        }

        return array(MailTypes::TYPE_ALL, MailTypes::TYPE_PRINT, MailTypes::TYPE_SCREEN);
    }
}
