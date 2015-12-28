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
use Sonatra\Bundle\MailerBundle\Model\LayoutInterface;
use Sonatra\Bundle\MailerBundle\Model\MailInterface;
use Sonatra\Bundle\MailerBundle\Util\TranslationUtil;
use Symfony\Component\Translation\TranslatorInterface;

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
     * @var TranslatorInterface|null
     */
    protected $translator;

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
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
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
        $mail = $this->getTranslatedMail($template, $type);
        $variables['_mail_type'] = $mail->getType();
        $subject = $this->renderTemplate($mail->getSubject(), $variables);
        $variables['_subject'] = $subject;
        $htmlBody = $this->renderTemplate($mail->getHtmlBody(), $variables);
        $variables['_html_body'] = $htmlBody;
        $body = $this->renderTemplate($mail->getBody(), $variables);
        $variables['_body'] = $body;

        $layout = $this->getTranslatedLayout($mail);

        if (null !== $layout && null !== ($lBody = $layout->getBody())) {
            $htmlBody = $this->renderTemplate($lBody, $variables);
        }

        return new MailRendered($mail, $subject, $htmlBody, $body);
    }

    /**
     * Get the translated mail.
     *
     * @param string $template The mail template name
     * @param string $type     The mail type defined in MailTypes::TYPE_*
     *
     * @return MailInterface
     */
    protected function getTranslatedMail($template, $type)
    {
        $mail = $this->loader->load($template, $type);

        return TranslationUtil::translate($mail, $this->getLocale(), $this->translator);
    }

    /**
     * Get the translated layout of mail.
     *
     * @param MailInterface $mail The mail
     *
     * @return LayoutInterface|null
     */
    protected function getTranslatedLayout(MailInterface $mail)
    {
        $layout = $mail->getLayout();

        if (null !== $layout) {
            $layout = TranslationUtil::translate($layout, $this->getLocale(), $this->translator);
        }

        return $layout;
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
