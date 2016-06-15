<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Tests\Filter\Template;

use Sonatra\Bundle\MailerBundle\Filter\Template\CssToStylesFilter;
use Sonatra\Bundle\MailerBundle\Mailer\MailRenderedInterface;
use Sonatra\Bundle\MailerBundle\MailTypes;
use Sonatra\Bundle\MailerBundle\Model\MailInterface;

/**
 * Tests for css to styles filter.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class CssToStylesFilterTest extends \PHPUnit_Framework_TestCase
{
    public function getSupportTests()
    {
        return array(
            array(MailTypes::TYPE_ALL,    true),
            array(MailTypes::TYPE_SCREEN, true),
            array(MailTypes::TYPE_PRINT,  false),
        );
    }

    /**
     * @dataProvider getSupportTests
     *
     * @param string $type      The mail type
     * @param bool   $supported Check if the mail type is supported
     */
    public function testSupports($type, $supported)
    {
        /* @var MailRenderedInterface|\PHPUnit_Framework_MockObject_MockObject $mailRendered */
        $mailRendered = $this->getMockBuilder(MailRenderedInterface::class)->getMock();
        $mailRendered->expects($this->once())
            ->method('getTemplate')
            ->will($this->returnCallback(function () use ($type) {
                $template = $this->getMockBuilder(MailInterface::class)->getMock();
                $template->expects($this->once())
                    ->method('getType')
                    ->will($this->returnValue($type));

                return $template;
            }));

        $filter = new CssToStylesFilter();

        $this->assertSame($supported, $filter->supports($mailRendered));
    }

    public function testFilter()
    {
        if(defined('HHVM_VERSION')) {
            $this->markTestSkipped('Bug: CssToInlineStyles::inlineCssOnElement() return a boolean on HHVM');

            return;
        }

        $html = '<html><head><style>p {color: #f7f7f7;}</style></head><body><p>Test.</p></body></html>';
        $htmlConverted = '<html><head><style>p {color: #f7f7f7;}</style></head><body><p style="color: #f7f7f7;">Test.</p></body></html>';
        $document = new \DOMDocument('1.0', 'utf-8');
        $document->loadHTML($htmlConverted);
        $document->formatOutput = true;
        $htmlConverted = $document->saveXML(null, LIBXML_NOEMPTYTAG);
        $htmlConverted = ltrim(preg_replace('|<\?xml (.*)\?>|', '', $htmlConverted));

        /* @var MailRenderedInterface|\PHPUnit_Framework_MockObject_MockObject $mailRendered */
        $mailRendered = $this->getMockBuilder(MailRenderedInterface::class)->getMock();
        $mailRendered->expects($this->at(0))
            ->method('getHtmlBody')
            ->will($this->returnValue($html));

        $mailRendered->expects($this->once())
            ->method('setHtmlBody')
            ->with($htmlConverted);

        $mailRendered->expects($this->at(2))
            ->method('getHtmlBody')
            ->will($this->returnValue($htmlConverted));

        $filter = new CssToStylesFilter();
        $filter->filter($mailRendered);

        $this->assertSame($htmlConverted, $mailRendered->getHtmlBody());
    }
}
