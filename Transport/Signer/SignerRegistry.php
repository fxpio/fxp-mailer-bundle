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

use Sonatra\Bundle\MailerBundle\Exception\InvalidArgumentException;

/**
 * Signer registry.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class SignerRegistry implements SignerRegistryInterface
{
    /**
     * @var SignerInterface[]
     */
    protected $signers;

    /**
     * Constructor.
     *
     * @param SignerInterface[] $signers The signers
     */
    public function __construct(array $signers)
    {
        foreach ($signers as $signer) {
            $this->addSigner($signer);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addSigner(SignerInterface $signer)
    {
        $this->signers[$signer->getName()] = $signer;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasSigner($name)
    {
        return isset($this->signers[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getSigner($name)
    {
        if (!isset($this->signers[$name])) {
            $msg = sprintf('The "%s" signer does not exist', $name);
            throw new InvalidArgumentException($msg);
        }

        return $this->signers[$name];
    }
}
