<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Transport\SwiftMailer;

/**
 * Base plugin for SwiftMailer.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
abstract class AbstractPlugin implements \Swift_Events_SendListener
{
    /**
     * @var array
     */
    protected $performed = array();

    /**
     * @var bool
     */
    protected $enabled = true;

    /**
     * Defined if the plugin must be enabled or disabled.
     *
     * @param bool $enabled The enabled value
     */
    public function setEnabled($enabled)
    {
        $this->enabled = (bool) $enabled;
    }

    /**
     * Check if the plugin is enabled.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }
}
