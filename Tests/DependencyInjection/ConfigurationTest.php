<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Bundle\MailerBundle\Tests\DependencyInjection;

use Fxp\Bundle\MailerBundle\DependencyInjection\Configuration;
use Fxp\Component\Mailer\Model\LayoutInterface;
use Fxp\Component\Mailer\Model\MailInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

/**
 * Tests for symfony extension configuration.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 *
 * @internal
 */
final class ConfigurationTest extends TestCase
{
    public function testDefaultConfig(): void
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), [[]]);

        $this->assertEquals(array_merge([], self::getBundleDefaultConfig()), $config);
    }

    public function testFilterConfig(): void
    {
        $valid = [
            'filters' => [
                'templates' => [
                    'css_to_styles' => [],
                ],
                'transports' => [],
            ],
        ];
        $config = [
            'filters' => [
                'templates' => [
                    'css_to_styles' => [],
                ],
            ],
        ];

        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), [$config]);

        $this->assertEquals(array_merge(self::getBundleDefaultConfig(), $valid), $config);
    }

    public function testInvalidFilterConfig(): void
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\InvalidConfigurationException::class);
        $this->expectExceptionMessage('The fxp_mailer.filters.templates config 42 must be either null or an array.');

        $config = [
            'filters' => [
                'templates' => [
                    'css_to_styles' => 42,
                ],
            ],
        ];

        $processor = new Processor();
        $processor->processConfiguration(new Configuration(), [$config]);
    }

    /**
     * @return array
     */
    protected static function getBundleDefaultConfig()
    {
        return [
            'layout_class' => LayoutInterface::class,
            'mail_class' => MailInterface::class,
            'layout_templates' => [],
            'mail_templates' => [],
            'filters' => [
                'templates' => [],
                'transports' => [],
            ],
            'transports' => [
                'swiftmailer' => [
                    'embed_image' => [
                        'enabled' => false,
                        'host_pattern' => '/(.*)+/',
                        'web_dir' => null,
                    ],
                    'dkim_signer' => [
                        'enabled' => false,
                        'private_key_path' => null,
                        'domain' => null,
                        'selector' => null,
                    ],
                ],
            ],
        ];
    }
}
