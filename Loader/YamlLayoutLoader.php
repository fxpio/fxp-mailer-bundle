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

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Yaml File layout loader.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class YamlLayoutLoader extends ConfigLayoutLoader
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * @var string[]|null
     */
    protected $resources;

    /**
     * Constructor.
     *
     * @param string|string[] $resources The resources
     * @param KernelInterface $kernel    The kernel
     */
    public function __construct($resources, KernelInterface $kernel)
    {
        parent::__construct(array());

        $this->kernel = $kernel;
        $this->resources = (array) $resources;
    }

    /**
     * {@inheritdoc}
     */
    public function load($name)
    {
        if (is_array($this->resources)) {
            foreach ($this->resources as $resource) {
                $filename = $this->kernel->locateResource($resource);
                $config = Yaml::parse(file_get_contents($filename));
                $this->addLayout($this->createLayout($config));
            }

            $this->resources = null;
        }

        return parent::load($name);
    }
}
