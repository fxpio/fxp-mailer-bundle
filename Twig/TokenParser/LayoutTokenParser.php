<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Twig\TokenParser;

use Sonatra\Bundle\MailerBundle\Twig\Node\ParentLayoutNode;

/**
 * Use mailer layout directly in twig template.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class LayoutTokenParser extends \Twig_TokenParser_Embed
{
    /**
     * {@inheritdoc}
     */
    public function parse(\Twig_Token $token)
    {
        $stream = $this->parser->getStream();

        $layout = $this->parser->getExpressionParser()->parseExpression();
        $parent = new ParentLayoutNode($layout, $token->getLine());

        list($variables, $only, $ignoreMissing) = $this->parseArguments();

        // inject a fake parent to make the parent() function work
        $stream->injectTokens(array(
            new \Twig_Token(\Twig_Token::BLOCK_START_TYPE, '', $token->getLine()),
            new \Twig_Token(\Twig_Token::NAME_TYPE, 'extends', $token->getLine()),
            new \Twig_Token(\Twig_Token::STRING_TYPE, '__parent__', $token->getLine()),
            new \Twig_Token(\Twig_Token::BLOCK_END_TYPE, '', $token->getLine()),
        ));

        $module = $this->parser->parse($stream, array($this, 'decideBlockEnd'), true);

        if (null === $module) {
            throw new \Twig_Error_Syntax('The decideBlockEnd method is wrong');
        }

        // override the parent with the correct one
        $module->setNode('parent', $parent);

        $this->parser->embedTemplate($module);

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        return new \Twig_Node_Embed($module->getAttribute('filename'), $module->getAttribute('index'), $variables, $only, $ignoreMissing, $token->getLine(), $this->getTag());
    }

    /**
     * Test the end of tag.
     *
     * @param \Twig_Token $token The token
     *
     * @return bool
     */
    public function decideBlockEnd(\Twig_Token $token)
    {
        return $token->test('endmailer_layout');
    }

    public function getTag()
    {
        return 'mailer_layout';
    }
}
