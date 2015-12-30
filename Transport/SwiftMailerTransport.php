<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Transport;

use Sonatra\Bundle\MailerBundle\Exception\InvalidArgumentException;
use Sonatra\Bundle\MailerBundle\Exception\UnexpectedTypeException;
use Sonatra\Bundle\MailerBundle\Mailer\MailRenderedInterface;
use Sonatra\Bundle\MailerBundle\Transport\Signer\Traits\TransportSignerTrait;
use Sonatra\Bundle\MailerBundle\Transport\Signer\TransportSignerInterface;

/**
 * SwiftMailer transport.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class SwiftMailerTransport implements TransportSignerInterface
{
    use TransportSignerTrait;

    /**
     * @var \Swift_Mailer
     */
    protected $swiftMailer;

    /**
     * Constructor.
     *
     * @param \Swift_Mailer $swiftMailer The swift mailer
     */
    public function __construct(\Swift_Mailer $swiftMailer)
    {
        $this->swiftMailer = $swiftMailer;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'swiftmailer';
    }

    /**
     * {@inheritdoc}
     */
    public function send($message, MailRenderedInterface $mailRendered = null)
    {
        $this->validate($message);

        /* @var \Swift_Message $message */

        if (null !== $mailRendered) {
            $message->setSubject($mailRendered->getSubject());
            $this->addBodies($message, $mailRendered);
        }

        if (null !== $signature = $this->getSignature()) {
            $message->attachSigner($signature);
        }

        $sent = $this->swiftMailer->send($message);

        return $sent > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($message)
    {
        if (!$message instanceof \Swift_Message) {
            throw new UnexpectedTypeException($message, \Swift_Message::class);
        }
    }

    /**
     * Add html and plain text bodies or only plain text if html is empty.
     *
     * @param \Swift_Message        $message      The swiftmailer message
     * @param MailRenderedInterface $mailRendered The rendered mail
     */
    protected function addBodies(\Swift_Message $message, MailRenderedInterface $mailRendered)
    {
        $textPlain = $mailRendered->getBody();
        $html = $mailRendered->getHtmlBody();

        if (null === $html) {
            $message->setBody($textPlain, 'text/plain');

            return;
        }

        $message->setBody($html, 'text/html');

        if (null !== $textPlain) {
            $message->addPart($textPlain, 'text/plain');
        }
    }

    /**
     * Get the signature.
     *
     * @return \Swift_Signers_DKIMSigner|null
     */
    protected function getSignature()
    {
        $signature = null;

        if (null !== $this->signerRegistry && null !== $this->signer) {
            $signer = $this->signerRegistry->getSigner($this->signer);
            $signature = $signer->createSignature();

            if (!$signature instanceof \Swift_Signers_DKIMSigner) {
                $msg = 'The signer "%s" must create a signature with an instance of Swift_Signers_DKIMSigner';
                throw new InvalidArgumentException(sprintf($msg, $signer->getName()));
            }
        }

        return $signature;
    }
}
