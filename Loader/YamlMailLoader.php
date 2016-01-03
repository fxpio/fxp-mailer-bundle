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
use Sonatra\Bundle\MailerBundle\Util\ConfigUtil;
use Symfony\Component\Yaml\Yaml;

/**
 * Yaml File mail loader.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class YamlMailLoader extends AbstractFileMailLoader
{
    /**
     * {@inheritdoc}
     */
    public function load($name, $type = MailTypes::TYPE_ALL)
    {
        if (is_array($this->resources)) {
            foreach ($this->resources as $resource) {
                $config = ConfigUtil::formatConfig($resource);
                $filename = $this->kernel->locateResource($resource);
                $loadedConfig = Yaml::parse(file_get_contents($filename));
                $this->addMail($this->createMail(array_replace($loadedConfig, $config)));
            }

            $this->resources = null;
        }

        return parent::load($name, $type);
    }
}
