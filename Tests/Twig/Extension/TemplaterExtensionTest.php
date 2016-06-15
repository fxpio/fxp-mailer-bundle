<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Tests\Twig\Extension;

use Sonatra\Bundle\MailerBundle\Loader\LayoutLoaderInterface;
use Sonatra\Bundle\MailerBundle\Mailer\MailRenderedInterface;
use Sonatra\Bundle\MailerBundle\Mailer\MailTemplaterInterface;
use Sonatra\Bundle\MailerBundle\MailTypes;
use Sonatra\Bundle\MailerBundle\Model\LayoutInterface;
use Sonatra\Bundle\MailerBundle\Model\TwigLayout;
use Sonatra\Bundle\MailerBundle\Twig\Extension\TemplaterExtension;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Tests for twig templater extension.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class TemplaterExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MailTemplaterInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $templater;

    /**
     * @var LayoutLoaderInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $layoutLoader;

    /**
     * @var TranslatorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $translator;

    /**
     * @var TemplaterExtension
     */
    protected $ext;

    protected function setUp()
    {
        $this->templater = $this->getMockBuilder(MailTemplaterInterface::class)->getMock();
        $this->layoutLoader = $this->getMockBuilder(LayoutLoaderInterface::class)->getMock();
        $this->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $this->ext = new TemplaterExtension($this->layoutLoader, $this->translator);

        /* @var ContainerInterface|\PHPUnit_Framework_MockObject_MockObject $container */
        $container = $this->getMockBuilder(ContainerInterface::class)->getMock();
        $container->expects($this->any())
            ->method('get')
            ->with('sonatra_mailer.mail_templater')
            ->will($this->returnValue($this->templater));

        $this->ext->container = $container;
    }

    public function testBasic()
    {
        $this->assertSame('sonatra_mailer_templater', $this->ext->getName());
        $this->assertCount(5, $this->ext->getFunctions());

        $valid = array(
            'sonatra_mailer_render_subject',
            'sonatra_mailer_render_html',
            'sonatra_mailer_render_text',
            'sonatra_mailer_mail_rendered',
            'sonatra_mailer_clean',
        );

        /* @var \Twig_SimpleFunction $function */
        foreach ($this->ext->getFunctions() as $function) {
            $this->assertInstanceOf(\Twig_SimpleFunction::class, $function);
            $this->assertTrue(in_array($function->getName(), $valid));
        }

        $this->assertCount(1, $this->ext->getTokenParsers());
    }

    public function testGetMailRendered()
    {
        /* @var string $template */
        /* @var array $variables */
        /* @var MailRenderedInterface|\PHPUnit_Framework_MockObject_MockObject $mail */
        list($template, $variables, $mail) = $this->getConfig();

        $rendered = $this->ext->getMailRendered($template, $variables);

        $this->assertSame($mail, $rendered);
    }

    public function testGetMailRenderedCache()
    {
        /* @var string $template */
        /* @var array $variables */
        /* @var MailRenderedInterface|\PHPUnit_Framework_MockObject_MockObject $mail */
        list($template, $variables, $mail) = $this->getConfig(true);

        $rendered = $this->ext->getMailRendered($template, $variables);

        $this->assertSame($mail, $rendered);

        $rendered2 = $this->ext->getMailRendered($template, $variables);

        $this->assertSame($rendered, $rendered2);

        $this->ext->cleanRendered($template);

        $rendered3 = $this->ext->getMailRendered($template, $variables);

        $this->assertNotSame($rendered, $rendered3);
    }

    public function testRenderSubject()
    {
        /* @var string $template */
        /* @var array $variables */
        /* @var MailRenderedInterface|\PHPUnit_Framework_MockObject_MockObject $mail */
        list($template, $variables, $mail) = $this->getConfig();
        $validSubject = 'Subject';

        $mail->expects($this->at(0))
            ->method('getSubject')
            ->with()
            ->will($this->returnValue($validSubject));

        $subject = $this->ext->renderSubject($template, $variables);

        $this->assertSame($validSubject, $subject);
    }

    public function testRenderHtml()
    {
        /* @var string $template */
        /* @var array $variables */
        /* @var MailRenderedInterface|\PHPUnit_Framework_MockObject_MockObject $mail */
        list($template, $variables, $mail) = $this->getConfig();
        $validHtml = '<p>Foo bar.</p>';

        $mail->expects($this->at(0))
            ->method('getHtmlBody')
            ->with()
            ->will($this->returnValue($validHtml));

        $html = $this->ext->renderHtml($template, $variables);

        $this->assertSame($validHtml, $html);
    }

    public function testRenderPlainText()
    {
        /* @var string $template */
        /* @var array $variables */
        /* @var MailRenderedInterface|\PHPUnit_Framework_MockObject_MockObject $mail */
        list($template, $variables, $mail) = $this->getConfig();
        $validPlainText = 'Foo bar.';

        $mail->expects($this->at(0))
            ->method('getBody')
            ->with()
            ->will($this->returnValue($validPlainText));

        $plainText = $this->ext->renderPlainText($template, $variables);

        $this->assertSame($validPlainText, $plainText);
    }

    public function testGetTranslatedLayout()
    {
        $layout = $this->getMockBuilder(TwigLayout::class)->disableOriginalConstructor()->getMock();

        $layout->expects($this->once())
            ->method('getTranslation')
            ->will($this->returnValue(clone $layout));

        $this->templater->expects($this->once())
            ->method('getLocale')
            ->will($this->returnValue('fr'));

        $this->layoutLoader->expects($this->once())
            ->method('load')
            ->with('test')
            ->will($this->returnValue($layout));

        $res = $this->ext->getTranslatedLayout('test');

        $this->assertInstanceOf(TwigLayout::class, $res);
        $this->assertNotSame($layout, $res);
    }

    /**
     * @expectedException \Sonatra\Bundle\MailerBundle\Exception\InvalidArgumentException
     * @expectedExceptionMessage The "test" layout is not a twig layout
     */
    public function testGetTranslatedLayoutWithInvalidLayout()
    {
        $layout = $this->getMockBuilder(LayoutInterface::class)->getMock();

        $layout->expects($this->once())
            ->method('getTranslation')
            ->will($this->returnValue(clone $layout));

        $this->templater->expects($this->once())
            ->method('getLocale')
            ->will($this->returnValue('fr'));

        $this->layoutLoader->expects($this->once())
            ->method('load')
            ->with('test')
            ->will($this->returnValue($layout));

        $this->ext->getTranslatedLayout('test');
    }

    /**
     * @param bool $clone
     *
     * @return array
     */
    public function getConfig($clone = false)
    {
        $template = 'test';
        $variables = array(
            'foo' => 'bar',
        );

        $mail = $this->getMockBuilder(MailRenderedInterface::class)->getMock();

        $this->templater->expects($this->at(0))
            ->method('render')
            ->with($template, $variables, MailTypes::TYPE_ALL)
            ->will($this->returnValue($mail));

        if ($clone) {
            $this->templater->expects($this->at(1))
                ->method('render')
                ->with($template, $variables, MailTypes::TYPE_ALL)
                ->will($this->returnValue(clone $mail));
        }

        return array($template, $variables, $mail);
    }
}
