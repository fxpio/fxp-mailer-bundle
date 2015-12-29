<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Doctrine\Loader;

use Sonatra\Bundle\MailerBundle\Doctrine\Loader\Traits\EntityLoaderTrait;
use Sonatra\Bundle\MailerBundle\Exception\UnknownMailException;
use Sonatra\Bundle\MailerBundle\Loader\MailLoaderInterface;
use Sonatra\Bundle\MailerBundle\MailTypes;
use Sonatra\Bundle\MailerBundle\Util\MailUtil;

/**
 * Entity mail loader.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class EntityMailLoader implements MailLoaderInterface
{
    use EntityLoaderTrait;

    /**
     * {@inheritdoc}
     */
    public function load($name, $type = MailTypes::TYPE_ALL)
    {
        $repo = $this->om->getRepository($this->class);
        $mail = $repo->findOneBy(array(
            'name' => $name,
            'enabled' => true,
            'type' => MailUtil::getValidTypes($type),
        ));

        if (null !== $mail) {
            return $mail;
        }

        throw new UnknownMailException($name, MailTypes::TYPE_ALL);
    }
}
