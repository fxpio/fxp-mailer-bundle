<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Entity;

use Sonatra\Bundle\MailerBundle\Model\MailTranslation as BaseMailTranslation;

/**
 * Entity for mail translation template.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class MailTranslation extends BaseMailTranslation
{
    /**
     * @var int|null
     */
    protected $id;

    /**
     * Get the id.
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }
}
