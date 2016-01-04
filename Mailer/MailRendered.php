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

use Sonatra\Bundle\MailerBundle\Model\MailInterface;

/**
 * The mail rendered.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class MailRendered implements MailRenderedInterface
{
    /**
     * @var MailInterface
     */
    protected $template;

    /**
     * @var string|null
     */
    protected $subject;

    /**
     * @var string|null
     */
    protected $htmlBody;

    /**
     * @var string|null
     */
    protected $body;

    /**
     * Constructor.
     *
     * @param MailInterface $template The mail template
     * @param string|null   $subject  The subject rendered
     * @param string|null   $htmlBody The HTML body rendered
     * @param string|null   $body     The body rendered
     */
    public function __construct(MailInterface $template, $subject, $htmlBody, $body)
    {
        $this->template = $template;
        $this->subject = $subject;
        $this->htmlBody = $htmlBody;
        $this->body = $body;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * {@inheritdoc}
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * {@inheritdoc}
     */
    public function setHtmlBody($htmlBody)
    {
        $this->htmlBody = $htmlBody;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getHtmlBody()
    {
        return $this->htmlBody;
    }

    /**
     * {@inheritdoc}
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->body;
    }
}
