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

    public function __construct(MailLoaderInterface $loader, \Twig_Environment $renderer)
    {
        $this->loader = $loader;
        $this->renderer = $renderer;
    }

    /**
     * {@inheritdoc}
     */
    public function render($template, $type = MailTypes::TYPE_ALL, array $variables = array())
    {
        $mail = $this->loader->load($template, $type);
        $variables['_mail_type'] = $mail->getType();

        $subject = $this->renderTemplate($mail->getSubject(), $variables);
        $htmlBody = $this->renderTemplate($mail->getHtmlBody(), $variables);
        $body = $this->renderTemplate($mail->getBody(), $variables);

        if (null !== $mail->getLayout() && null !== ($lBody = $mail->getLayout()->getBody())) {
            $htmlBody = $this->renderTemplate($lBody, array_merge($variables, array(
                '_mail_content_body' => $htmlBody,
            )));
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
