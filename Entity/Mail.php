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
use Sonatra\Bundle\MailerBundle\Model\Mail as BaseMail;

/**
 * Entity for mail template.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class Mail extends BaseMail
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

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }
}
