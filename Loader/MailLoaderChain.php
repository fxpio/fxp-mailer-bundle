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

use Sonatra\Bundle\MailerBundle\Exception\UnknownMailException;
use Sonatra\Bundle\MailerBundle\MailTypes;

/**
 * Mail loader chain.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class MailLoaderChain implements MailLoaderInterface
{
    /**
     * @var MailLoaderInterface[]
     */
    protected $loaders;

    /**
     * Constructor.
     *
     * @param MailLoaderInterface[] $loaders The layout loaders
     */
    public function __construct(array $loaders)
    {
        $this->loaders = array();

        foreach ($loaders as $loader) {
            $this->addLoader($loader);
        }
    }

    /**
     * Add the layout template loader.
     *
     * @param MailLoaderInterface $loader The layout loader
     */
    public function addLoader(MailLoaderInterface $loader)
    {
        $this->loaders[] = $loader;
    }

    /**
     * {@inheritdoc}
     */
    public function load($name, $type = MailTypes::TYPE_ALL)
    {
        foreach ($this->loaders as $loader) {
            try {
                return $loader->load($name, $type);
            } catch (UnknownMailException $e) {
                // do nothing, check the next loader
            }
        }

        throw new UnknownMailException($name, $type);
    }
}
