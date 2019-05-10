<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Bundle\MailerBundle\Tests\DependencyInjection\Compiler;

use Fxp\Bundle\MailerBundle\DependencyInjection\Compiler\OptimizeTwigTemplateLoaderPass;
use Fxp\Component\Mailer\Loader\ArrayTemplateLayoutLoader;
use Fxp\Component\Mailer\Loader\TwigTemplateLayoutLoader;
use Fxp\Component\Mailer\Mailer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Tests for optimize twig loader pass.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 *
 * @internal
 */
final class OptimizeTwigTemplateLoaderPassTest extends KernelTestCase
{
    /**
     * @var string
     */
    protected $rootDir;

    /**
     * @var Filesystem
     */
    protected $fs;

    /**
     * @var OptimizeTwigTemplateLoaderPass
     */
    protected $pass;

    protected function setUp(): void
    {
        $this->rootDir = sys_get_temp_dir().'/fxp_mailer_bundle_optimize_twig_loader_pass';
        $this->fs = new Filesystem();
        $this->pass = new OptimizeTwigTemplateLoaderPass();
    }

    protected function tearDown(): void
    {
        $this->fs->remove($this->rootDir);
        $this->pass = null;
    }

    public function testProcessWithoutService(): void
    {
        $container = $this->getContainer();

        $this->pass->process($container);
        $this->assertFalse($container->has('fxp_mailer.loader.template_layout_twig'));
        $this->assertFalse($container->has('fxp_mailer.loader.template_mail_twig'));
    }

    public function testProcessWithAddTemplates(): void
    {
        $container = $this->getContainer();
        $layoutLoaderDef = new Definition(TwigTemplateLayoutLoader::class);
        $mailLoaderDef = new Definition(TwigTemplateLayoutLoader::class);
        $refMailer = new \ReflectionClass(Mailer::class);
        $mailerBaseDir = \dirname($refMailer->getFileName());

        $layoutLoaderDef->setArguments([[]]);
        $mailLoaderDef->setArguments([[]]);

        $container->setDefinition('fxp_mailer.loader.template_layout_twig', $layoutLoaderDef);
        $container->setDefinition('fxp_mailer.loader.template_mail_twig', $mailLoaderDef);

        $this->assertCount(0, $layoutLoaderDef->getArgument(0));
        $this->assertCount(0, $mailLoaderDef->getArgument(0));

        // array loader
        $layoutArrayLoaderDef = new Definition(ArrayTemplateLayoutLoader::class);
        $mailArrayLoaderDef = new Definition(ArrayTemplateLayoutLoader::class);

        $layoutArrayLoaderDef->setArguments([[]]);
        $mailArrayLoaderDef->setArguments([[]]);

        $container->setDefinition('fxp_mailer.loader.template_layout_array', $layoutArrayLoaderDef);
        $container->setDefinition('fxp_mailer.loader.template_mail_array', $mailArrayLoaderDef);

        $this->assertCount(0, $layoutArrayLoaderDef->getArgument(0));
        $this->assertCount(0, $mailArrayLoaderDef->getArgument(0));

        // add mocks
        $layoutLoaderDef->replaceArgument(0, [
            [
                'name' => 'layout-test',
                'file' => $mailerBaseDir.'/Tests/Fixtures/loaders/layout.html.twig',
                'translations' => [
                    [
                        'locale' => 'fr',
                        'file' => $mailerBaseDir.'/Tests/Fixtures/loaders/layout.fr.html.twig',
                    ],
                ],
            ],
        ]);
        $mailLoaderDef->replaceArgument(0, [
            [
                'name' => 'mail-test',
                'file' => $mailerBaseDir.'/Tests/Fixtures/loaders/mail.html.twig',
                'layout' => 'layout-test',
                'translations' => [
                    [
                        'locale' => 'fr',
                        'file' => $mailerBaseDir.'/Tests/Fixtures/loaders/mail.fr.html.twig',
                    ],
                ],
            ],
        ]);

        // test
        $this->pass->process($container);
        $this->assertFalse($container->has('fxp_mailer.loader.template_layout_twig'));
        $this->assertFalse($container->has('fxp_mailer.loader.template_mail_twig'));
    }

    /**
     * Gets the container.
     *
     * @return ContainerBuilder
     */
    protected function getContainer()
    {
        return new ContainerBuilder(new ParameterBag([
            'kernel.cache_dir' => $this->rootDir,
            'kernel.debug' => false,
            'kernel.environment' => 'test',
            'kernel.name' => 'kernel',
            'kernel.root_dir' => $this->rootDir,
            'kernel.charset' => 'UTF-8',
            'kernel.bundles' => [],
        ]));
    }
}
