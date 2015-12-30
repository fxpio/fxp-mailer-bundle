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

use Sonatra\Bundle\MailerBundle\Transport\TransportInterface;

/**
 * Interface for the signer.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
interface TransportSignerInterface extends TransportInterface
{
    /**
     * Set the signer registry.
     *
     * @param SignerRegistryInterface $signerRegistry The signer registry
     *
     * @return self
     */
    public function setSignerRegistry(SignerRegistryInterface $signerRegistry);

    /**
     * Set the signer for the transport must be used when message is sent.
     *
     * @param string $name The name of the signer
     *
     * @return self
     */
    public function setSigner($name);
}
