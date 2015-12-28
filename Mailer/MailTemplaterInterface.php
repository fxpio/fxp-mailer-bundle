<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Mailer;

use Sonatra\Bundle\MailerBundle\MailTypes;

/**
 * Interface for the mail templater.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
interface MailTemplaterInterface
{
    /**
     * Render the mail template.
     *
     * @param string $template  The mail template name
     * @param array  $variables The variables of template
     * @param string $type      The mail type defined in MailTypes::TYPE_*
     *
     * @return MailRenderedInterface
     */
    public function render($template, array $variables = array(), $type = MailTypes::TYPE_ALL);
}
