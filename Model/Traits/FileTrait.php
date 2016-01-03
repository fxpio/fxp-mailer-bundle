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

/**
 * Trait for file model.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
trait FileTrait
{
    /**
     * @var string
     */
    protected $file;

    /**
     * {@inheritdoc}
     */
    public function setFile($file)
    {
        $this->support($file);
        $this->file = $file;

        return $file;
    }

    /**
     * {@inheritdoc}
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Check if the file is supported.
     *
     * @param string|null $file The file name
     *
     * @throws \Sonatra\Bundle\MailerBundle\Exception\InvalidArgumentException When the file is not supported
     */
    abstract protected function support($file);
}
