<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class event for the template.pre_render event.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class FilterPreRenderEvent extends Event
{
    /**
     * @var string
     */
    protected $template;

    /**
     * @var array
     */
    protected $variables;

    /**
     * @var string
     */
    protected $type;

    /**
     * Constructor.
     *
     * @param string $template  The mail template name
     * @param array  $variables The variables of template
     * @param string $type      The mail type defined in MailTypes::TYPE_*
     */
    public function __construct($template, array $variables, $type)
    {
        $this->template = $template;
        $this->variables = $variables;
        $this->type = $type;
    }

    /**
     * Set the template.
     *
     * @param string $template The template
     *
     * @return self
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get the template.
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set the variables.
     *
     * @param array $variables The variables
     *
     * @return self
     */
    public function setVariables($variables)
    {
        $this->variables = $variables;

        return $this;
    }

    /**
     * Get the variables.
     *
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * Set the mail type.
     *
     * @param string $type The mail type defined in MailTypes::TYPE_*
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the mail type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
