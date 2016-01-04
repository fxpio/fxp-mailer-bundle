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

use Sonatra\Bundle\MailerBundle\Event\FilterPostRenderEvent;
use Sonatra\Bundle\MailerBundle\Event\FilterPreRenderEvent;
use Sonatra\Bundle\MailerBundle\Loader\MailLoaderInterface;
use Sonatra\Bundle\MailerBundle\MailerEvents;
use Sonatra\Bundle\MailerBundle\MailTypes;
use Sonatra\Bundle\MailerBundle\Model\LayoutInterface;
use Sonatra\Bundle\MailerBundle\Model\MailInterface;
use Sonatra\Bundle\MailerBundle\Model\TwigTemplateInterface;
use Sonatra\Bundle\MailerBundle\Util\MailUtil;
use Sonatra\Bundle\MailerBundle\Util\TranslationUtil;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

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
     * @param MailLoaderInterface      $loader     The mail loader
     * @param \Twig_Environment        $renderer   The twig environment
     * @param EventDispatcherInterface $dispatcher The event dispatcher
     */
    public function __construct(MailLoaderInterface $loader, \Twig_Environment $renderer,
                                EventDispatcherInterface $dispatcher)
    {
        $this->loader = $loader;
        $this->renderer = $renderer;
        $this->locale = \Locale::getDefault();
        $this->dispatcher = $dispatcher;
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
        $preEvent = new FilterPreRenderEvent($template, $variables, $type);
        $this->dispatcher->dispatch(MailerEvents::TEMPLATE_PRE_RENDER, $preEvent);

        $mail = $this->getTranslatedMail($preEvent->getTemplate(), $preEvent->getType());
        $mailerBuilder = $this->doRender($preEvent, $mail);

        $postEvent = new FilterPostRenderEvent($mailerBuilder);
        $this->dispatcher->dispatch(MailerEvents::TEMPLATE_POST_RENDER, $postEvent);

        return $postEvent->getMailRenderedBuilder()->build();
    }

    /**
     * Render the mail.
     *
     * @param FilterPreRenderEvent $preEvent The template pre event
     * @param MailInterface        $mail     The mail
     *
     * @return MailRenderedBuilder
     */
    protected function doRender(FilterPreRenderEvent $preEvent, MailInterface $mail)
    {
        $variables = $preEvent->getVariables();
        $variables['_mail_type'] = $mail->getType();
        $variables['_layout'] = null !== $mail->getLayout() ? $mail->getLayout()->getName() : null;
        $subject = $this->renderTemplate($mail->getSubject(), $mail, $variables);
        $variables['_subject'] = $subject;
        $htmlBody = $this->renderTemplate($mail->getHtmlBody(), $mail, $variables);
        $variables['_html_body'] = $htmlBody;
        $body = $this->renderTemplate($mail->getBody(), $mail, $variables);
        $variables['_body'] = $body;

        $layout = $this->getTranslatedLayout($mail);

        if (null !== $layout && null !== ($lBody = $layout->getBody()) && !MailUtil::isRootBody($htmlBody)) {
            $htmlBody = $this->renderTemplate($lBody, $layout, $variables);
        }

        return new MailRenderedBuilder($mail, $subject, $htmlBody, $body);
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

        return TranslationUtil::translateMail($mail, $this->getLocale(), $this->translator);
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
            $layout = TranslationUtil::translateLayout($layout, $this->getLocale(), $this->translator);
        }

        return $layout;
    }

    /**
     * Render the template.
     *
     * @param string                        $template         The template string
     * @param LayoutInterface|MailInterface $templateInstance The template instance
     * @param array                         $variables        The variables of template
     *
     * @return string The rendered template
     *
     * @throws \Exception
     */
    protected function renderTemplate($template, $templateInstance, array $variables = array())
    {
        if (null !== $template) {
            if ($templateInstance instanceof TwigTemplateInterface) {
                $tpl = $this->renderer->loadTemplate($templateInstance->getFile());

                if ($tpl instanceof \Twig_Template) {
                    $template = $tpl->renderBlock($template, $variables);
                    $template = '' === $template ? null : $template;
                }
            } else {
                $tpl = $this->renderer->createTemplate($template);
                $template = $tpl->render($variables);
            }
        }

        return $template;
    }
}
