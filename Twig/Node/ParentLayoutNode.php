<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Twig\Node;

/**
 * Get the filename of layout translated template.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ParentLayoutNode extends \Twig_Node_Expression
{
    /**
     * Constructor.
     *
     * @param \Twig_Node_Expression $variables
     * @param int                   $lineno
     * @param string                $tag
     */
    public function __construct(\Twig_Node_Expression $variables, $lineno, $tag = null)
    {
        $attr = array('variables' => $variables);
        parent::__construct(array(), $attr, $lineno, $tag);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param \Twig_Compiler $compiler A Twig_Compiler instance
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->raw('$this->env->getExtension(\'Sonatra\Bundle\MailerBundle\Twig\Extension\TemplaterExtension\')')
            ->raw('->getTranslatedLayout(')
            ->subcompile($this->getAttribute('variables'))
            ->raw(')->getFile()')
        ;
    }
}
