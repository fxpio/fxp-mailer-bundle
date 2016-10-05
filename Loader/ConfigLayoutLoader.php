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

use Sonatra\Bundle\MailerBundle\Model\Layout;
use Sonatra\Bundle\MailerBundle\Model\LayoutInterface;
use Sonatra\Bundle\MailerBundle\Model\LayoutTranslation;
use Sonatra\Bundle\MailerBundle\Model\LayoutTranslationInterface;
use Sonatra\Bundle\MailerBundle\Util\ConfigUtil;

/**
 * Config layout loader.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ConfigLayoutLoader extends ArrayLayoutLoader
{
    /**
     * Constructor.
     *
     * @param array[] $configs The layout config
     */
    public function __construct(array $configs)
    {
        $layouts = array();

        foreach ($configs as $config) {
            $layouts[] = $this->createLayout($config);
        }

        parent::__construct($layouts);
    }

    /**
     * Create the layout.
     *
     * @param array $config The config of layout
     *
     * @return LayoutInterface
     */
    protected function createLayout(array $config)
    {
        $layout = $this->newLayoutInstance();

        $layout->setName(ConfigUtil::getValue($config, 'name'));
        $layout->setLabel(ConfigUtil::getValue($config, 'label'));
        $layout->setDescription(ConfigUtil::getValue($config, 'description'));
        $layout->setEnabled(ConfigUtil::getValue($config, 'enabled', true));
        $layout->setBody(ConfigUtil::getValue($config, 'body'));
        $layout->setTranslationDomain(ConfigUtil::getValue($config, 'translation_domain'));

        if (isset($config['translations']) && is_array($config['translations'])) {
            foreach ($config['translations'] as $translation) {
                $layout->addTranslation($this->createLayoutTranslation($layout, $translation));
            }
        }

        return $layout;
    }

    /**
     * Create a layout translation.
     *
     * @param LayoutInterface $layout The layout
     * @param array           $config The config of layout translation
     *
     * @return LayoutTranslation
     */
    protected function createLayoutTranslation(LayoutInterface $layout, array $config)
    {
        $translation = $this->newLayoutTranslationInstance($layout);
        $translation->setLocale(ConfigUtil::getValue($config, 'locale'));
        $translation->setLabel(ConfigUtil::getValue($config, 'label'));
        $translation->setDescription(ConfigUtil::getValue($config, 'description'));
        $translation->setBody(ConfigUtil::getValue($config, 'body'));

        return $translation;
    }

    /**
     * Create a new instance of layout.
     *
     * @return LayoutInterface
     */
    protected function newLayoutInstance()
    {
        return new Layout();
    }

    /**
     * Create a new instance of layout translation.
     *
     * @param LayoutInterface $layout The layout
     *
     * @return LayoutTranslationInterface
     */
    protected function newLayoutTranslationInstance(LayoutInterface $layout)
    {
        return new LayoutTranslation($layout);
    }
}
