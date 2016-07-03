<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Model;

use Sonatra\Bundle\MailerBundle\Exception\InvalidArgumentException;
use Sonatra\Bundle\MailerBundle\Model\Traits\FileTrait;

/**
 * Model for twig file mail translation.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class TwigMailTranslation extends MailTranslation implements TwigTemplateInterface
{
    use FileTrait;

    /**
     * Constructor.
     *
     * @param MailInterface $mail The mail
     * @param string|null   $file The file name
     */
    public function __construct(MailInterface $mail, $file = null)
    {
        parent::__construct($mail);

        $this->setFile($file);
        $this->subject = 'subject';
        $this->htmlBody = 'html_body';
        $this->body = 'body';
    }

    /**
     * {@inheritdoc}
     */
    protected function support($file)
    {
        if (null !== $file && 'twig' !== pathinfo($file, PATHINFO_EXTENSION)) {
            $msg = 'The "%s" file is not supported by the mail translation file template';
            throw new InvalidArgumentException(sprintf($msg, $file));
        }
    }
}
