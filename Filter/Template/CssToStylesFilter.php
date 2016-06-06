<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Filter\Template;

use Sonatra\Bundle\MailerBundle\Filter\TemplateFilterInterface;
use Sonatra\Bundle\MailerBundle\Mailer\MailRenderedInterface;
use Sonatra\Bundle\MailerBundle\MailTypes;
use Sonatra\Bundle\MailerBundle\Util\MailUtil;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

/**
 * Filter for convert the inline CSS to inline styles.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class CssToStylesFilter implements TemplateFilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function filter(MailRenderedInterface $mailRendered)
    {
        $cssToInlineStyles = new CssToInlineStyles();

        $mailRendered->setHtmlBody($cssToInlineStyles->convert($mailRendered->getHtmlBody()));
    }

    /**
     * {@inheritdoc}
     */
    public function supports(MailRenderedInterface $mailRendered)
    {
        $validTypes = MailUtil::getValidTypes($mailRendered->getTemplate()->getType());

        return in_array(MailTypes::TYPE_SCREEN, $validTypes);
    }
}
