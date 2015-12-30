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

/**
 * Interface for the signer.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
interface SignerInterface
{
    /**
     * Get the name.
     *
     * @return string
     */
    public function getName();

    /**
     * Return the signature for the specific transport.
     *
     * @return mixed
     */
    public function createSignature();
}
