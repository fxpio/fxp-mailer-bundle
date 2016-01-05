<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Tests\DependencyInjection;

use Sonatra\Bundle\MailerBundle\DependencyInjection\Configuration;
use Sonatra\Bundle\MailerBundle\Model\LayoutInterface;
use Sonatra\Bundle\MailerBundle\Model\MailInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;

/**
 * Tests for symfony extension configuration.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultConfig()
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), array(array()));

        $this->assertEquals(array_merge(array(), self::getBundleDefaultConfig()), $config);
    }

    public function testFilterConfig()
    {
        $valid = array(
            'filters' => array(
                'templates' => array(
                    'csstostyles' => array(),
                ),
                'transports' => array(),
            ),
        );
        $config = array(
            'filters' => array(
                'templates' => array(
                    'csstostyles' => array(),
                ),
            ),
        );

        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), array($config));

        $this->assertEquals(array_merge(self::getBundleDefaultConfig(), $valid), $config);
    }

    public function testInvalidFilterConfig()
    {
        $msg = 'The sonatra_mailer.filters.templates config 42 must be either null or an array.';
        $this->setExpectedException(InvalidConfigurationException::class, $msg);

        $config = array(
            'filters' => array(
                'templates' => array(
                    'csstostyles' => 42,
                ),
            ),
        );

        $processor = new Processor();
        $processor->processConfiguration(new Configuration(), array($config));
    }

    /**
     * @return array
     */
    protected static function getBundleDefaultConfig()
    {
        return array(
            'layout_class' => LayoutInterface::class,
            'mail_class' => MailInterface::class,
            'layout_templates' => array(),
            'mail_templates' => array(),
            'transport_signers' => array(
                'signers' => array(),
                'swiftmailer_dkim' => array(
                    'private_key_path' => null,
                    'domain' => null,
                    'selector' => null,
                ),
            ),
            'filters' => array(
                'templates' => array(),
                'transports' => array(),
            ),

        );
    }
}
