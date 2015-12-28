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
use Sonatra\Bundle\MailerBundle\MailTypes;
use Sonatra\Bundle\MailerBundle\Model\Traits\TranslationTrait;

/**
 * Model for mail template.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class Mail extends AbstractTemplate implements MailInterface
{
    use TranslationTrait;

    /**
     * @var string
     */
    protected $type = MailTypes::TYPE_ALL;

    /**
     * @var string|null
     */
    protected $subject;

    /**
     * @var string|null
     */
    protected $htmlBody;

    /**
     * @var LayoutInterface|null
     */
    protected $layout;

    /**
     * @var MailTranslationInterface[]|Collection
     */
    protected $translations = array();

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * {@inheritdoc}
     */
    public function setHtmlBody($htmlBody)
    {
        $this->htmlBody = $htmlBody;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getHtmlBody()
    {
        return $this->htmlBody;
    }

    /**
     * {@inheritdoc}
     */
    public function setLayout(LayoutInterface $layout)
    {
        $this->layout = $layout;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLayout()
    {
        return $this->layout;
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
    public function addTranslation(MailTranslationInterface $translation)
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
    public function removeTranslation(MailTranslationInterface $translation)
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
