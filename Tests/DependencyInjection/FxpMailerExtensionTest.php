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

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\DoctrineExtension;
use Fxp\Bundle\MailerBundle\DependencyInjection\FxpMailerExtension;
use Fxp\Bundle\MailerBundle\FxpMailerBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\FrameworkExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

/**
 * Tests for symfony extension.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 *
 * @internal
 */
final class FxpMailerExtensionTest extends TestCase
{
    public function testExtensionExist(): void
    {
        $container = $this->createContainer();

        $this->assertTrue($container->hasExtension('fxp_mailer'));
    }

    public function testExtensionLoader(): void
    {
        $container = $this->createContainer();

        $this->assertTrue($container->hasDefinition('fxp_mailer.mail_templater'));
        $this->assertTrue($container->hasDefinition('fxp_mailer.loader.template_layout_chain'));
        $this->assertTrue($container->hasDefinition('fxp_mailer.loader.template_mail_chain'));
        $this->assertTrue($container->hasDefinition('fxp_mailer.loader.template_layout_array'));
        $this->assertTrue($container->hasDefinition('fxp_mailer.loader.template_mail_array'));
        $this->assertFalse($container->hasDefinition('fxp_mailer.loader.template_layout_config'));
        $this->assertFalse($container->hasDefinition('fxp_mailer.loader.template_mail_config'));
        $this->assertFalse($container->hasDefinition('fxp_mailer.loader.template_layout_yaml'));
        $this->assertFalse($container->hasDefinition('fxp_mailer.loader.template_mail_yaml'));
    }

    public function testAddTemplates(): void
    {
        $container = $this->createContainer([
            [
                'layout_templates' => [
                    [
                        'name' => 'layout-test',
                        'loader' => 'config',
                    ],
                ],
                'mail_templates' => [
                    [
                        'name' => 'mail-test',
                        'loader' => 'config',
                        'layout' => 'layout-test',
                    ],
                ],
            ],
        ]);

        $this->assertTrue($container->hasDefinition('fxp_mailer.loader.template_layout_array'));
        $this->assertTrue($container->hasDefinition('fxp_mailer.loader.template_mail_array'));

        $layout = $container->getDefinition('fxp_mailer.loader.template_layout_array');
        $this->assertCount(1, $layout->getArguments());
        $this->assertCount(1, $layout->getArgument(0));

        $mail = $container->getDefinition('fxp_mailer.loader.template_mail_array');
        $this->assertCount(2, $mail->getArguments());
        $this->assertCount(1, $mail->getArgument(0));
    }

    public function testAddFilters(): void
    {
        $container = $this->createContainer([
            [
                'filters' => [
                    'templates' => [
                        'css_to_styles' => [
                            'foo' => 'bar',
                            'bar' => 'foo',
                        ],
                    ],
                ],
            ],
        ]);

        $this->assertTrue($container->hasDefinition('fxp_mailer.filter.template.css_to_styles'));
        $this->assertTrue($container->hasParameter('fxp_mailer.filter.template.css_to_styles.foo'));
        $this->assertTrue($container->hasParameter('fxp_mailer.filter.template.css_to_styles.bar'));
    }

    public function testEnableSwiftMailerEmbedImagePlugin(): void
    {
        $container = $this->createContainer([
            [
                'transports' => [
                    'swiftmailer' => [
                        'embed_image' => true,
                    ],
                ],
            ],
        ]);

        $this->assertTrue($container->hasDefinition('fxp_mailer.transport.swiftmailer.embed_image_plugin'));
    }

    public function testEnableSwiftMailerDkimSignerPlugin(): void
    {
        $container = $this->createContainer([
            [
                'transports' => [
                    'swiftmailer' => [
                        'dkim_signer' => [
                            'enabled' => true,
                            'private_key_path' => 'private_key_path',
                            'domain' => 'domain',
                            'selector' => 'selector',
                        ],
                    ],
                ],
            ],
        ]);

        $this->assertTrue($container->hasDefinition('fxp_mailer.transport.swiftmailer.dkim_signer_plugin'));
    }

    protected function createContainer(array $configs = [])
    {
        $container = new ContainerBuilder(new ParameterBag([
            'kernel.bundles' => [
                'FrameworkBundle' => 'Symfony\\Bundle\\FrameworkBundle\\FrameworkBundle',
                'FxpMailerBundle' => 'Fxp\\Bundle\\MailerBundle\\FxpMailerBundle',
            ],
            'kernel.bundles_metadata' => [],
            'kernel.cache_dir' => sys_get_temp_dir().'/fxp_mailer_bundle',
            'kernel.debug' => false,
            'kernel.environment' => 'test',
            'kernel.name' => 'kernel',
            'kernel.project_dir' => sys_get_temp_dir().'/fxp_mailer_bundle',
            'kernel.root_dir' => sys_get_temp_dir().'/fxp_mailer_bundle/app',
            'kernel.charset' => 'UTF-8',
        ]));

        $sfExt = new FrameworkExtension();
        $doctrineExt = new DoctrineExtension();
        $extension = new FxpMailerExtension();

        $container->registerExtension($sfExt);
        $container->registerExtension($doctrineExt);
        $container->registerExtension($extension);

        $sfExt->load([[]], $container);
        $doctrineExt->load([$this->getDoctrineConfig()], $container);
        $extension->load($configs, $container);

        $bundle = new FxpMailerBundle();
        $bundle->build($container);

        $optimizationPasses = [];

        foreach ($container->getCompilerPassConfig()->getOptimizationPasses() as $pass) {
            if (0 === strpos(\get_class($pass), 'Fxp\Bundle\MailerBundle\DependencyInjection\Compiler')) {
                $optimizationPasses[] = $pass;
            }
        }

        $container->getCompilerPassConfig()->setOptimizationPasses($optimizationPasses);
        $container->getCompilerPassConfig()->setRemovingPasses([]);
        $container->compile();

        return $container;
    }

    protected function getDoctrineConfig()
    {
        return [
            'dbal' => [
                'default_connection' => 'default',
                'connections' => [
                    'default' => [
                        'driver' => 'pdo_sqlite',
                        'path' => '%kernel.cache_dir%/test.db',
                    ],
                ],
            ],
            'orm' => [
                'auto_generate_proxy_classes' => true,
                'entity_managers' => [
                    'default' => [
                        'auto_mapping' => true,
                    ],
                ],
            ],
        ];
    }
}
