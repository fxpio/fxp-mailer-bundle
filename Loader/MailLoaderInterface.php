<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Loader;

use Sonatra\Bundle\MailerBundle\Exception\UnknownMailException;
use Sonatra\Bundle\MailerBundle\MailTypes;
use Sonatra\Bundle\MailerBundle\Model\MailInterface;

/**
 * Interface for the mail loader.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
interface MailLoaderInterface
{
    /**
     * Load the mail template.
     *
     * @param string $name The unique name of mail template
     * @param string $type The mail type defined in MailTypes::TYPE_*
     *
     * @return MailInterface
     *
     * @throws UnknownMailException When the mail template does not exist
     */
    public function load($name, $type = MailTypes::TYPE_ALL);
}
