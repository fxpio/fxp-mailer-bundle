<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle;

/**
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
final class MailerEvents
{
    /**
     * The template.pre_render event is thrown before that an mail must be rendered.
     *
     * The event listener receives an
     * Sonatra\Bundle\MailerBundle\Event\FilterPreRenderEvent instance.
     *
     * @var string
     */
    const TEMPLATE_PRE_RENDER = 'sonatra_mailer.template.pre_render';

    /**
     * The template.post_render event is thrown after that an mail is rendered.
     *
     * The event listener receives an
     * Sonatra\Bundle\MailerBundle\Event\FilterPostRenderEvent instance.
     *
     * @var string
     */
    const TEMPLATE_POST_RENDER = 'sonatra_mailer.template.post_render';
}
