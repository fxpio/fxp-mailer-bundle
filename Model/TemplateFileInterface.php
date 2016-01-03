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

/**
 * Interface for the template file.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
interface TemplateFileInterface
{
    /**
     * Set the file name.
     *
     * @param string $file
     *
     * @return self
     */
    public function setFile($file);

    /**
     * Get the file name.
     *
     * @return string
     */
    public function getFile();
}
