<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Twig\Extension;

use Sonatra\Bundle\MailerBundle\Mailer\MailRenderedInterface;
use Sonatra\Bundle\MailerBundle\Mailer\MailTemplaterInterface;
use Sonatra\Bundle\MailerBundle\MailTypes;

/**
 * Use the mail templater directly in twig template.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class TemplaterExtension extends \Twig_Extension
{
    /**
     * @var MailTemplaterInterface
     */
    protected $templater;

    /**
     * Constructor.
     *
     * @param MailTemplaterInterface $templater The templater
     */
    public function __construct(MailTemplaterInterface $templater)
    {
        $this->templater = $templater;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('sonatra_mailer_render', array($this, 'renderHtml'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('sonatra_mailer_render_plain', array($this, 'renderPlainText')),
            new \Twig_SimpleFunction('sonatra_mailer_mail_rendered', array($this, 'getMailRendered')),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sonatra_mailer_templater';
    }

    /**
     * Render the mail template in html.
     *
     * @param string $template  The mail template name
     * @param array  $variables The variables of template
     * @param string $type      The mail type defined in MailTypes::TYPE_*
     *
     * @return string
     */
    public function renderHtml($template, array $variables = array(), $type = MailTypes::TYPE_ALL)
    {
        return $this->getMailRendered($template, $variables, $type)->getHtmlBody();
    }

    /**
     * Render the mail template in plain text.
     *
     * @param string $template  The mail template name
     * @param array  $variables The variables of template
     * @param string $type      The mail type defined in MailTypes::TYPE_*
     *
     * @return string
     */
    public function renderPlainText($template, array $variables = array(), $type = MailTypes::TYPE_ALL)
    {
        return $this->getMailRendered($template, $variables, $type)->getBody();
    }

    /**
     * Render the mail template.
     *
     * @param string $template  The mail template name
     * @param array  $variables The variables of template
     * @param string $type      The mail type defined in MailTypes::TYPE_*
     *
     * @return MailRenderedInterface
     */
    public function getMailRendered($template, array $variables = array(), $type = MailTypes::TYPE_ALL)
    {
        return $this->templater->render($template, $variables, $type);
    }
}
