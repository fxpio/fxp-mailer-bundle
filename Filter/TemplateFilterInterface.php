<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Filter;

use Sonatra\Bundle\MailerBundle\Mailer\MailRenderedInterface;

/**
 * Interface for the template filter.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
interface TemplateFilterInterface
{
    /**
     * Filter the mail rendered.
     *
     * @param MailRenderedInterface $mailRendered The mail rendered
     */
    public function filter(MailRenderedInterface $mailRendered);

    /**
     * Check if the filter is compatible with the mail rendered.
     *
     * @param MailRenderedInterface $mailRendered The mail rendered
     *
     * @return bool
     */
    public function supports(MailRenderedInterface $mailRendered);
}
