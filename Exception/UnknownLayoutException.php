<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Exception;

/**
 * Unknown Layout Template Exception.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class UnknownLayoutException extends InvalidArgumentException
{
    /**
     * Constructor.
     *
     * @param string $name The layout template name
     */
    public function __construct($name)
    {
        parent::__construct(sprintf('The "%s" layout template does not exist', $name));
    }
}
