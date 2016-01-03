<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Tests\Twig\TokenParser;

use Sonatra\Bundle\MailerBundle\Twig\TokenParser\LayoutTokenParser;

/**
 * Tests for twig layout token parser.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class LayoutTokenParserTest extends \PHPUnit_Framework_TestCase
{
    public function testBasic()
    {
        $layout = $this->getMockBuilder(\Twig_Node_Expression::class)->disableOriginalConstructor()->getMock();

        $expressionParser = $this->getMockBuilder(\Twig_ExpressionParser::class)->disableOriginalConstructor()->getMock();
        $expressionParser->expects($this->any())
            ->method('parseExpression')
            ->will($this->returnValue($layout));

        $stream = $this->getMockBuilder(\Twig_TokenStream::class)->disableOriginalConstructor()->getMock();

        /* @var \Twig_Parser|\PHPUnit_Framework_MockObject_MockObject $parser */
        $parser = $this->getMockBuilder(\Twig_Parser::class)->disableOriginalConstructor()->getMock();
        $parser->expects($this->any())
            ->method('getStream')
            ->will($this->returnValue($stream));

        $parser->expects($this->any())
            ->method('getExpressionParser')
            ->will($this->returnValue($expressionParser));

        $tokenParser = new LayoutTokenParser();
        $tokenParser->setParser($parser);

        $module = $this->getMockBuilder(\Twig_Node_Module::class)->disableOriginalConstructor()->getMock();

        $parser->expects($this->once())
            ->method('parse')
            ->with($stream, array($tokenParser, 'decideBlockEnd'), true)
            ->will($this->returnValue($module));

        /* @var \Twig_Token|\PHPUnit_Framework_MockObject_MockObject $token */
        $token = $this->getMockBuilder(\Twig_Token::class)->disableOriginalConstructor()->getMock();
        $token->expects($this->any())
            ->method('getLine')
            ->will($this->returnValue(42));

        $token->expects($this->any())
            ->method('test')
            ->will($this->returnValue(true));

        $tokenParser->parse($token);

        $tokenParser->decideBlockEnd($token);

        $this->assertSame('mailer_layout', $tokenParser->getTag());
    }

    public function testInvalidModule()
    {
        $this->setExpectedException(\Twig_Error_Syntax::class, 'The decideBlockEnd method is wrong');

        $layout = $this->getMockBuilder(\Twig_Node_Expression::class)->disableOriginalConstructor()->getMock();

        $expressionParser = $this->getMockBuilder(\Twig_ExpressionParser::class)->disableOriginalConstructor()->getMock();
        $expressionParser->expects($this->any())
            ->method('parseExpression')
            ->will($this->returnValue($layout));

        $stream = $this->getMockBuilder(\Twig_TokenStream::class)->disableOriginalConstructor()->getMock();

        /* @var \Twig_Parser|\PHPUnit_Framework_MockObject_MockObject $parser */
        $parser = $this->getMockBuilder(\Twig_Parser::class)->disableOriginalConstructor()->getMock();
        $parser->expects($this->any())
            ->method('getStream')
            ->will($this->returnValue($stream));

        $parser->expects($this->any())
            ->method('getExpressionParser')
            ->will($this->returnValue($expressionParser));

        $tokenParser = new LayoutTokenParser();
        $tokenParser->setParser($parser);

        $parser->expects($this->once())
            ->method('parse')
            ->with($stream, array($tokenParser, 'decideBlockEnd'), true)
            ->will($this->returnValue(null));

        /* @var \Twig_Token|\PHPUnit_Framework_MockObject_MockObject $token */
        $token = $this->getMockBuilder(\Twig_Token::class)->disableOriginalConstructor()->getMock();
        $token->expects($this->any())
            ->method('getLine')
            ->will($this->returnValue(42));

        $token->expects($this->any())
            ->method('test')
            ->will($this->returnValue(true));

        $tokenParser->parse($token);
    }
}
