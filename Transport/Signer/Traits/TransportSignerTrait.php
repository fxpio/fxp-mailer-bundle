<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Transport\Signer\Traits;

use Sonatra\Bundle\MailerBundle\Transport\Signer\SignerRegistryInterface;

/**
 * Trait for transport signer.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
trait TransportSignerTrait
{
    /**
     * @var SignerRegistryInterface|null
     */
    protected $signerRegistry;

    /**
     * @var string|null
     */
    protected $signer;

    /**
     * {@inheritdoc}
     */
    public function setSignerRegistry(SignerRegistryInterface $signerRegistry)
    {
        $this->signerRegistry = $signerRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function setSigner($name)
    {
        $this->signer = $name;
    }
}
