<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Mailer;

use Sonatra\Bundle\MailerBundle\Loader\MailLoaderInterface;
use Sonatra\Bundle\MailerBundle\MailTypes;

/**
 * The mail templater.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class MailTemplater implements MailTemplaterInterface
{
    /**
     * @var MailLoaderInterface
     */
    protected $loader;

    /**
     * @var \Twig_Environment
     */
    protected $renderer;

    /**
     * @var string
     */
    protected $locale;

    /**
     * Constructor.
     *
     * @param MailLoaderInterface $loader   The mail loader
     * @param \Twig_Environment   $renderer The twig environment
     */
    public function __construct(MailLoaderInterface $loader, \Twig_Environment $renderer)
    {
        $this->loader = $loader;
        $this->renderer = $renderer;
        $this->locale = \Locale::getDefault();
    }

    /**
     * {@inheritdoc}
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * {@inheritdoc}
     */
    public function render($template, array $variables = array(), $type = MailTypes::TYPE_ALL)
    {
        $mail = $this->loader->load($template, $type)->getTranslation($this->getLocale());
        $variables['_mail_type'] = $mail->getType();
        $subject = $this->renderTemplate($mail->getSubject(), $variables);
        $variables['_subject'] = $subject;
        $htmlBody = $this->renderTemplate($mail->getHtmlBody(), $variables);
        $variables['_html_body'] = $htmlBody;
        $body = $this->renderTemplate($mail->getBody(), $variables);
        $variables['_body'] = $body;

        if (null !== $mail->getLayout()
                && null !== ($lBody = $mail->getLayout()->getTranslation($this->getLocale())->getBody())) {
            $htmlBody = $this->renderTemplate($lBody, $variables);
        }

        return new MailRendered($mail, $subject, $htmlBody, $body);
    }

    /**
     * Render the template.
     *
     * @param string $template  The template string
     * @param array  $variables The variables of template
     *
     * @return string The rendered template
     *
     * @throws \Exception
     */
    protected function renderTemplate($template, array $variables = array())
    {
        if (null !== $template) {
            $tpl = $this->renderer->createTemplate($template);
            $template = $tpl->render($variables);
        }

        return $template;
    }
}
