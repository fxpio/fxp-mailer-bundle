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

use Doctrine\Common\Collections\ArrayCollection;
use Sonatra\Bundle\MailerBundle\Model\Layout as BaseLayout;

/**
 * Entity for layout template.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class Layout extends BaseLayout
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

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->mails = new ArrayCollection();
        $this->translations = new ArrayCollection();
    }
}
