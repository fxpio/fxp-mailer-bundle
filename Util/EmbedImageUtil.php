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
 * Utils for swiftmailer embed image plugin.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
abstract class EmbedImageUtil
{
    /**
     * Get the local path of file.
     *
     * @param string $path        The path
     * @param string $webDir      The absolute web directory
     * @param string $hostPattern The pattern of allowed host
     *
     * @return string
     */
    public static function getLocalPath($path, $webDir, $hostPattern = '/(.*)+/')
    {
        if (false !== strpos($path, '://')) {
            $url = parse_url($path);

            if (isset($url['host']) && isset($url['path'])
                    && preg_match($hostPattern, $url['host'], $matches)) {
                $path = static::getExistingPath($url['path'], $webDir);
            }
        } else {
            $path = static::getExistingPath($path, $webDir);
        }

        return $path;
    }

    /**
     * Get the the absolute path if file exists.
     *
     * @param string $path   The path
     * @param string $webDir The absolute web directory
     *
     * @return string
     */
    protected static function getExistingPath($path, $webDir)
    {
        return file_exists($webDir.'/'.$path)
            ? realpath($webDir.'/'.$path)
            : $path;
    }
}
