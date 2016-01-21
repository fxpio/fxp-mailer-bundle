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

use Sonatra\Bundle\MailerBundle\Model\LayoutTranslation as BaseLayoutTranslation;

/**
 * Entity for layout translation template.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class LayoutTranslation extends BaseLayoutTranslation
{
    /**
     * @var int|string|null
     */
    protected $id;

    /**
     * Get the id.
     *
     * @return int|string|null
     */
    public function getId()
    {
        return $this->id;
    }
}
