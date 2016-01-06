<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Transport\SwiftMailer;

use Sonatra\Bundle\MailerBundle\Exception\RuntimeException;

/**
 * SwiftMailer DKIM Signer Plugin.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class DkimSignerPlugin extends AbstractPlugin
{
    /**
     * @var string
     */
    protected $privateKeyPath;
    /**
     * @var string
     */
    protected $domain;
    /**
     * @var string
     */
    protected $selector;
    /**
     * Constructor.
     *
     * @param string $privateKeyPath The path of the private key
     * @param string $domain         The DKIM domain
     * @param string $selector       The DKIM selector
     */
    public function __construct($privateKeyPath, $domain, $selector)
    {
        $this->privateKeyPath = $privateKeyPath;
        $this->domain = $domain;
        $this->selector = $selector;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSendPerformed(\Swift_Events_SendEvent $event)
    {
        $message = $event->getMessage();

        if (!$this->isEnabled() || !$message instanceof \Swift_Message
                || in_array($message->getId(), $this->performed)) {
            return;
        }

        $signature = new \Swift_Signers_DKIMSigner($this->getPrivateKey(), $this->domain, $this->selector);
        $message->attachSigner($signature);
        $this->performed[] = $message->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function sendPerformed(\Swift_Events_SendEvent $event)
    {
        // not used
    }

    /**
     * Get the private key.
     *
     * @return string
     *
     * @throws RuntimeException When the private key cannot be read
     */
    protected function getPrivateKey()
    {
        try {
            $privateKey = file_get_contents($this->privateKeyPath);
        } catch (\Exception $e) {
            $msg = 'Impossible to read the private key of the DKIM swiftmailer signer "%s"';
            throw new RuntimeException(sprintf($msg, $this->privateKeyPath));
        }

        return $privateKey;
    }
}
