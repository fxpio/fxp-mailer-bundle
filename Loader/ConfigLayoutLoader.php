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
use Sonatra\Bundle\MailerBundle\Model\LayoutTranslation;
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
        foreach ($configs as $i => $config) {
            $configs[$i] = $this->createLayout($config);
        }

        parent::__construct($configs);
    }

    /**
     * Create the layout.
     *
     * @param array $config The config of layout.
     *
     * @return Layout
     */
    protected function createLayout(array $config)
    {
        $layout = new Layout();

        $layout->setName(ConfigUtil::getValue($config, 'name'));
        $layout->setLabel(ConfigUtil::getValue($config, 'label', ConfigUtil::getValue($config, 'name')));
        $layout->setDescription(ConfigUtil::getValue($config, 'description'));
        $layout->setEnabled(ConfigUtil::getValue($config, 'enabled', true));
        $layout->setBody(ConfigUtil::getValue($config, 'body'));

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
     * @param Layout $layout The layout
     * @param array  $config The config of layout translation
     *
     * @return LayoutTranslation
     */
    protected function createLayoutTranslation(Layout $layout, array $config)
    {
        $translation = new LayoutTranslation($layout);
        $translation->setLocale(ConfigUtil::getValue($config, 'locale'));
        $translation->setLabel(ConfigUtil::getValue($config, 'label'));
        $translation->setDescription(ConfigUtil::getValue($config, 'description'));
        $translation->setBody(ConfigUtil::getValue($config, 'body'));

        return $translation;
    }
}
