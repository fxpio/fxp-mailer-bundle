<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Transport\Signer;

use Sonatra\Bundle\MailerBundle\Exception\RuntimeException;

/**
 * The signer for the swiftmailer DKIM signature.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class SwiftMailerDkimSigner implements SignerInterface
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
    public function getName()
    {
        return 'swiftmailer_dkim';
    }

    /**
     * {@inheritdoc}
     *
     * @return \Swift_Signers_DKIMSigner
     */
    public function createSignature()
    {
        return new \Swift_Signers_DKIMSigner($this->getPrivateKey(), $this->domain, $this->selector);
    }

    /**
     * Get the private key.
     *
     * @return string
     */
    protected function getPrivateKey()
    {
        if (!is_readable($this->privateKeyPath)) {
            $msg = 'The private key path of the DKIM swiftmailer signer "%s" does not exist or is not readable';
            throw new RuntimeException(sprintf($msg, $this->privateKeyPath));
        }

        try {
            $privateKey = file_get_contents($this->privateKeyPath);
        } catch (\Exception $e) {
            $msg = 'Impossible to read the private key of the DKIM swiftmailer signer "%s"';
            throw new RuntimeException(sprintf($msg, $this->privateKeyPath));
        }

        return $privateKey;
    }
}
