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

/**
 * Utils for config.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
abstract class ConfigUtil
{
    /**
     * Get the value.
     *
     * @param array      $config  The config
     * @param string     $field   The field
     * @param mixed|null $default The default value if the field does not exist
     */
    public static function getValue(array $config, $field, $default = null)
    {
        return isset($config[$field])
            ? $config[$field]
            : $default;
    }
}
