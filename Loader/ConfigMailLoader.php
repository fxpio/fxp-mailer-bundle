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

use Sonatra\Bundle\MailerBundle\MailTypes;
use Sonatra\Bundle\MailerBundle\Model\Mail;
use Sonatra\Bundle\MailerBundle\Model\MailTranslation;
use Sonatra\Bundle\MailerBundle\Util\ConfigUtil;

/**
 * Config mail loader.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ConfigMailLoader extends ArrayMailLoader
{
    /**
     * @var LayoutLoaderInterface
     */
    protected $layoutLoader;

    /**
     * Constructor.
     *
     * @param array[]               $configs      The mail config
     * @param LayoutLoaderInterface $layoutLoader The layout loader
     */
    public function __construct(array $configs, LayoutLoaderInterface $layoutLoader)
    {
        $this->layoutLoader = $layoutLoader;

        foreach ($configs as $i => $config) {
            $configs[$i] = $this->createMail($config);
        }

        parent::__construct($configs);
    }

    /**
     * Create the mail.
     *
     * @param array $config
     *
     * @return Mail
     */
    protected function createMail(array $config)
    {
        $mail = new Mail();

        $mail->setName(ConfigUtil::getValue($config, 'name'));
        $mail->setLabel(ConfigUtil::getValue($config, 'label', ConfigUtil::getValue($config, 'name')));
        $mail->setDescription(ConfigUtil::getValue($config, 'description'));
        $mail->setType(ConfigUtil::getValue($config, 'type', MailTypes::TYPE_ALL));
        $mail->setEnabled(ConfigUtil::getValue($config, 'enabled', true));
        $mail->setSubject(ConfigUtil::getValue($config, 'subject'));
        $mail->setHtmlBody(ConfigUtil::getValue($config, 'html_body'));
        $mail->setBody(ConfigUtil::getValue($config, 'body'));

        if (isset($config['layout'])) {
            $mail->setLayout($this->layoutLoader->load($config['layout']));
        }

        if (isset($config['translations']) && is_array($config['translations'])) {
            foreach ($config['translations'] as $translation) {
                $mail->addTranslation($this->createLayoutTranslation($mail, $translation));
            }
        }

        return $mail;
    }

    /**
     * Create a mail translation.
     *
     * @param Mail  $mail   The mail
     * @param array $config The config of mail translation
     *
     * @return MailTranslation
     */
    protected function createLayoutTranslation(Mail $mail, array $config)
    {
        $translation = new MailTranslation($mail);
        $translation->setLocale(ConfigUtil::getValue($config, 'locale'));
        $translation->setLabel(ConfigUtil::getValue($config, 'label'));
        $translation->setDescription(ConfigUtil::getValue($config, 'description'));
        $translation->setSubject(ConfigUtil::getValue($config, 'subject'));
        $translation->setHtmlBody(ConfigUtil::getValue($config, 'html_body'));
        $translation->setBody(ConfigUtil::getValue($config, 'body'));

        return $translation;
    }
}
