<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Tests\Loader;

use Sonatra\Bundle\MailerBundle\Exception\UnknownMailException;
use Sonatra\Bundle\MailerBundle\Loader\ConfigMailLoader;
use Sonatra\Bundle\MailerBundle\Loader\LayoutLoaderInterface;
use Sonatra\Bundle\MailerBundle\MailTypes;
use Sonatra\Bundle\MailerBundle\Model\LayoutInterface;
use Sonatra\Bundle\MailerBundle\Model\MailInterface;

/**
 * Tests for config mail loader.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ConfigMailLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testLoad()
    {
        // layout
        $templateLayout = $this->getMock(LayoutInterface::class);
        $templateLayout->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('test'));
        $templateLayout->expects($this->any())
            ->method('isEnabled')
            ->will($this->returnValue(true));

        // mail
        $template = array(
            'name' => 'test',
            'label' => 'Test',
            'description' => 'Description of test',
            'type' => MailTypes::TYPE_ALL,
            'enabled' => true,
            'subject' => 'Subject of mail with {{ twig_variable }}',
            'html_body' => '<p>HTML content of mail with {{ twig_variable }}.</p>',
            'body' => 'Content of mail with {{ twig_variable }}.',
            'layout' => 'test',
            'translations' => array(
                array(
                    'locale' => 'fr',
                    'label' => 'Test fr',
                    'description' => 'Description du test',
                    'subject' => 'Sujet du courrier avec {{ twig_variable }}',
                    'html_body' => '<p>Contenu HTML du courrier avec {{ twig_variable }}.</p>',
                    'body' => 'Contenu du courrier avec {{ twig_variable }}.',
                ),
            ),
        );

        /* @var LayoutLoaderInterface|\PHPUnit_Framework_MockObject_MockObject $layoutLoader */
        $layoutLoader = $this->getMock(LayoutLoaderInterface::class);
        $layoutLoader->expects($this->once())
            ->method('load')
            ->will($this->returnValue($templateLayout));

        $loader = new ConfigMailLoader(array($template), $layoutLoader);

        $mail = $loader->load('test');

        $this->assertInstanceOf(MailInterface::class, $mail);
        $this->assertInstanceOf(LayoutInterface::class, $mail->getLayout());
    }

    public function testLoadUnknownTemplate()
    {
        $this->setExpectedException(UnknownMailException::class, 'The "test" mail template does not exist with the "all" type');
        /* @var LayoutLoaderInterface $layoutLoader */
        $layoutLoader = $this->getMock(LayoutLoaderInterface::class);

        $loader = new ConfigMailLoader(array(), $layoutLoader);

        $loader->load('test');
    }
}
