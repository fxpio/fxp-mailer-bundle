<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Model\Traits;

use Sonatra\Bundle\MailerBundle\Model\LayoutInterface;
use Sonatra\Bundle\MailerBundle\Model\MailInterface;
use Sonatra\Bundle\MailerBundle\Util\TranslationUtil;

/**
 * Trait for translation model.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
trait TranslationTrait
{
    /**
     * @var array
     */
    protected $cacheTranslation = array();

    /**
     * {@inheritdoc}
     */
    public function getTranslation($locale)
    {
        if (isset($this->cacheTranslation[$locale])) {
            return $this->cacheTranslation[$locale];
        }

        /* @var LayoutInterface|MailInterface|TranslationTrait $this */
        $self = clone $this;

        if (!TranslationUtil::find($self, $locale) && false !== ($pos = strrpos($locale, '_'))) {
            TranslationUtil::find($self, substr($locale, 0, $pos));
        }

        $this->cacheTranslation[$locale] = $self;

        return $self;
    }
}
