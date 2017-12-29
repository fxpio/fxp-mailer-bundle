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
 */
class FxpMailerExtensionTest extends TestCase
{
    public function testExtensionExist()
    {
        $container = $this->createContainer();

        $this->assertTrue($container->hasExtension('fxp_mailer'));
    }

    public function testExtensionLoader()
    {
        $container = $this->createContainer();

        $this->assertTrue($container->hasDefinition('fxp_mailer.mail_templater'));
        $this->assertTrue($container->hasDefinition('fxp_mailer.loader.layout_chain'));
        $this->assertTrue($container->hasDefinition('fxp_mailer.loader.mail_chain'));
        $this->assertTrue($container->hasDefinition('fxp_mailer.loader.layout_array'));
        $this->assertTrue($container->hasDefinition('fxp_mailer.loader.mail_array'));
        $this->assertFalse($container->hasDefinition('fxp_mailer.loader.layout_config'));
        $this->assertFalse($container->hasDefinition('fxp_mailer.loader.mail_config'));
        $this->assertFalse($container->hasDefinition('fxp_mailer.loader.layout_yaml'));
        $this->assertFalse($container->hasDefinition('fxp_mailer.loader.mail_yaml'));
    }

    public function testAddTemplates()
    {
        $container = $this->createContainer(array(
            array(
                'layout_templates' => array(
                    array(
                        'name' => 'layout-test',
                        'loader' => 'config',
                    ),
                ),
                'mail_templates' => array(
                    array(
                        'name' => 'mail-test',
                        'loader' => 'config',
                        'layout' => 'layout-test',
                    ),
                ),
            ),
        ));

        $this->assertTrue($container->hasDefinition('fxp_mailer.loader.layout_array'));
        $this->assertTrue($container->hasDefinition('fxp_mailer.loader.mail_array'));

        $layout = $container->getDefinition('fxp_mailer.loader.layout_array');
        $this->assertCount(1, $layout->getArguments());
        $this->assertCount(1, $layout->getArgument(0));

        $mail = $container->getDefinition('fxp_mailer.loader.mail_array');
        $this->assertCount(2, $mail->getArguments());
        $this->assertCount(1, $mail->getArgument(0));
    }

    public function testAddFilters()
    {
        $container = $this->createContainer(array(
            array(
                'filters' => array(
                    'templates' => array(
                        'css_to_styles' => array(
                            'foo' => 'bar',
                            'bar' => 'foo',
                        ),
                    ),
                ),
            ),
        ));

        $this->assertTrue($container->hasDefinition('fxp_mailer.filter.template.css_to_styles'));
        $this->assertTrue($container->hasParameter('fxp_mailer.filter.template.css_to_styles.foo'));
        $this->assertTrue($container->hasParameter('fxp_mailer.filter.template.css_to_styles.bar'));
    }

    public function testEnableSwiftMailerEmbedImagePlugin()
    {
        $container = $this->createContainer(array(
            array(
                'transports' => array(
                    'swiftmailer' => array(
                        'embed_image' => true,
                    ),
                ),
            ),
        ));

        $this->assertTrue($container->hasDefinition('fxp_mailer.transport.swiftmailer.embed_image_plugin'));
    }

    public function testEnableSwiftMailerDkimSignerPlugin()
    {
        $container = $this->createContainer(array(
            array(
                'transports' => array(
                    'swiftmailer' => array(
                        'dkim_signer' => array(
                            'enabled' => true,
                            'private_key_path' => 'private_key_path',
                            'domain' => 'domain',
                            'selector' => 'selector',
                        ),
                    ),
                ),
            ),
        ));

        $this->assertTrue($container->hasDefinition('fxp_mailer.transport.swiftmailer.dkim_signer_plugin'));
    }

    protected function createContainer(array $configs = array())
    {
        $container = new ContainerBuilder(new ParameterBag(array(
            'kernel.bundles' => array(
                'FrameworkBundle' => 'Symfony\\Bundle\\FrameworkBundle\\FrameworkBundle',
                'FxpMailerBundle' => 'Fxp\\Bundle\\MailerBundle\\FxpMailerBundle',
            ),
            'kernel.bundles_metadata' => array(),
            'kernel.cache_dir' => sys_get_temp_dir().'/fxp_mailer_bundle',
            'kernel.debug' => false,
            'kernel.environment' => 'test',
            'kernel.name' => 'kernel',
            'kernel.project_dir' => sys_get_temp_dir().'/fxp_mailer_bundle',
            'kernel.root_dir' => sys_get_temp_dir().'/fxp_mailer_bundle/app',
            'kernel.charset' => 'UTF-8',
        )));

        $sfExt = new FrameworkExtension();
        $doctrineExt = new DoctrineExtension();
        $extension = new FxpMailerExtension();

        $container->registerExtension($sfExt);
        $container->registerExtension($doctrineExt);
        $container->registerExtension($extension);

        $sfExt->load(array(array()), $container);
        $doctrineExt->load(array($this->getDoctrineConfig()), $container);
        $extension->load($configs, $container);

        $bundle = new FxpMailerBundle();
        $bundle->build($container);

        $optimizationPasses = array();

        foreach ($container->getCompilerPassConfig()->getOptimizationPasses() as $pass) {
            if (0 === strpos(get_class($pass), 'Fxp\Bundle\MailerBundle\DependencyInjection\Compiler')) {
                $optimizationPasses[] = $pass;
            }
        }

        $container->getCompilerPassConfig()->setOptimizationPasses($optimizationPasses);
        $container->getCompilerPassConfig()->setRemovingPasses(array());
        $container->compile();

        return $container;
    }

    protected function getDoctrineConfig()
    {
        return array(
            'dbal' => array(
                'default_connection' => 'default',
                'connections' => array(
                    'default' => array(
                        'driver' => 'pdo_sqlite',
                        'path' => '%kernel.cache_dir%/test.db',
                    ),
                ),
            ),
            'orm' => array(
                'auto_generate_proxy_classes' => true,
                'entity_managers' => array(
                    'default' => array(
                        'auto_mapping' => true,
                    ),
                ),
            ),
        );
    }
}
