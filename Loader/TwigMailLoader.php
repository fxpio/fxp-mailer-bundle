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

use Sonatra\Bundle\MailerBundle\Model\MailInterface;
use Sonatra\Bundle\MailerBundle\Model\TwigMail;
use Sonatra\Bundle\MailerBundle\Model\TwigMailTranslation;
use Sonatra\Bundle\MailerBundle\Util\ConfigUtil;

/**
 * Twig File mail loader.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class TwigMailLoader extends AbstractFileMailLoader
{
    /**
     * {@inheritdoc}
     */
    protected function createMail(array $config)
    {
        /* @var $mail TwigMail */
        $mail = parent::createMail($config);
        $mail->setFile(ConfigUtil::getValue($config, 'file'));

        return $mail;
    }

    /**
     * {@inheritdoc}
     */
    protected function createMailTranslation(MailInterface $mail, array $config)
    {
        /* @var $translation TwigMailTranslation */
        $translation = parent::createMailTranslation($mail, $config);
        $translation->setFile(ConfigUtil::getValue($config, 'file'));

        return $translation;
    }

    /**
     * {@inheritdoc}
     */
    protected function newMailInstance()
    {
        return new TwigMail();
    }

    /**
     * {@inheritdoc}
     */
    protected function newMailTranslationInstance(MailInterface $mail)
    {
        return new TwigMailTranslation($mail);
    }
}
