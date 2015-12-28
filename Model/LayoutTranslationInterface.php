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
 * Interface for the layout translation template.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
interface LayoutTranslationInterface extends TemplateTranslationInterface
{
    /**
     * Get the reference layout.
     *
     * @return LayoutInterface
     */
    public function getLayout();
}
