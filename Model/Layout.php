<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Model;

use Doctrine\Common\Collections\Collection;
use Sonatra\Bundle\MailerBundle\Model\Traits\TranslationTrait;

/**
 * Model for layout template.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class Layout implements LayoutInterface
{
    use TranslationTrait;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $label;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var bool
     */
    protected $enabled = true;

    /**
     * @var string|null
     */
    protected $body;

    /**
     * @var MailInterface[]|Collection
     */
    protected $mails = array();

    /**
     * @var LayoutTranslationInterface[]|Collection
     */
    protected $translations = array();

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function setEnabled($enabled)
    {
        $this->enabled = (bool) $enabled;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * {@inheritdoc}
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * {@inheritdoc}
     */
    public function getMails()
    {
        return $this->mails;
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * {@inheritdoc}
     */
    public function addTranslation(LayoutTranslationInterface $translation)
    {
        if ($this->translations instanceof Collection) {
            if (!$this->translations->contains($translation)) {
                $this->translations->add($translation);
            }
        } else {
            $this->translations[$translation->getLocale()] = $translation;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeTranslation(LayoutTranslationInterface $translation)
    {
        if ($this->translations instanceof Collection) {
            if ($this->translations->contains($translation)) {
                $this->translations->removeElement($translation);
            }
        } else {
            unset($this->translations[$translation->getLocale()]);
        }

        return $this;
    }
}
