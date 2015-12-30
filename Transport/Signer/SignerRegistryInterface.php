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
 * Interface for the signer registry.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
interface SignerRegistryInterface
{
    /**
     * Add the signer.
     *
     * @param SignerInterface $signer The signer
     *
     * @return self
     */
    public function addSigner(SignerInterface $signer);

    /**
     * Check if the signer is present.
     *
     * @param string $name The name of the signer
     *
     * @return bool
     */
    public function hasSigner($name);

    /**
     * Get the signer.
     *
     * @param string $name The name of the signer
     *
     * @return SignerInterface
     *
     * @throws InvalidArgumentException When the signer does not exist
     */
    public function getSigner($name);
}
