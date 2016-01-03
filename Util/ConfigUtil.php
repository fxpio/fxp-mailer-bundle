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

use Sonatra\Bundle\MailerBundle\Exception\InvalidConfigurationException;
use Sonatra\Bundle\MailerBundle\Exception\UnexpectedTypeException;
use Symfony\Component\HttpKernel\KernelInterface;

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
     *
     * @return mixed
     */
    public static function getValue(array $config, $field, $default = null)
    {
        return isset($config[$field])
            ? $config[$field]
            : $default;
    }

    /**
     * Format the string config to array config with "file" attribute.
     *
     * @param string|array $config The config
     *
     * @return array
     */
    public static function formatConfig($config)
    {
        if (is_string($config)) {
            $config = array('file' => $config);
        }

        if (!is_array($config)) {
            throw new UnexpectedTypeException($config, 'array');
        }

        if (!isset($config['file'])) {
            $msg = 'The "file" attribute must be defined in config of layout template';
            throw new InvalidConfigurationException($msg);
        }

        return $config;
    }

    /**
     * Format the string config to array config with "file" attribute and translations.
     *
     * @param string|array    $config The config
     * @param KernelInterface $kernel The kernel
     *
     * @return array
     */
    public static function formatTranslationConfig($config, KernelInterface $kernel)
    {
        $config = static::formatConfig($config);
        $config['file'] = $kernel->locateResource($config['file']);

        if (isset($config['translations']) && is_array($config['translations'])) {
            /* @var array $translation */
            foreach ($config['translations'] as &$translation) {
                $translation['file'] = $kernel->locateResource($translation['file']);
            }
        }

        return $config;
    }
}
