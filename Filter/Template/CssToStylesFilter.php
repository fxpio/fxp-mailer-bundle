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
     * @var bool
     */
    protected $cleanup = false;

    /**
     * @var bool
     */
    protected $useInlineStylesBlock = true;

    /**
     * @var bool
     */
    protected $stripOriginalStyleTags = false;

    /**
     * @var bool
     */
    protected $excludeMediaQueries = false;

    /**
     * @var bool
     */
    protected $outputXhtml = false;

    /**
     * {@inheritdoc}
     */
    public function filter(MailRenderedInterface $mailRendered)
    {
        $cssToInlineStyles = new CssToInlineStyles();
        $cssToInlineStyles->setHTML($mailRendered->getHtmlBody());
        $cssToInlineStyles->setCleanup($this->cleanup);
        $cssToInlineStyles->setUseInlineStylesBlock($this->useInlineStylesBlock);
        $cssToInlineStyles->setStripOriginalStyleTags($this->stripOriginalStyleTags);
        $cssToInlineStyles->setExcludeMediaQueries($this->excludeMediaQueries);

        $mailRendered->setHtmlBody($cssToInlineStyles->convert($this->outputXhtml));
    }

    /**
     * {@inheritdoc}
     */
    public function supports(MailRenderedInterface $mailRendered)
    {
        $validTypes = MailUtil::getValidTypes($mailRendered->getTemplate()->getType());

        return in_array(MailTypes::TYPE_SCREEN, $validTypes);
    }

    /**
     * Should the generated HTML be cleaned?
     *
     * @param bool $cleanup The option value
     */
    public function setCleanup($cleanup)
    {
        $this->cleanup = $cleanup;
    }

    /**
     * Use inline-styles block as CSS.
     *
     * @param bool $useInlineStylesBlock The option value
     */
    public function setUseInlineStylesBlock($useInlineStylesBlock)
    {
        $this->useInlineStylesBlock = $useInlineStylesBlock;
    }

    /**
     * Strip original style tags.
     *
     * @param bool $stripOriginalStyleTags The option value
     */
    public function setStripOriginalStyleTags($stripOriginalStyleTags)
    {
        $this->stripOriginalStyleTags = $stripOriginalStyleTags;
    }

    /**
     * Exclude the media queries from the inlined styles.
     *
     * @param bool $excludeMediaQueries The option value
     */
    public function setExcludeMediaQueries($excludeMediaQueries)
    {
        $this->excludeMediaQueries = $excludeMediaQueries;
    }

    /**
     * Should we output valid XHTML?
     *
     * @param bool $outputXhtml The option value
     */
    public function setOutputXhtml($outputXhtml)
    {
        $this->outputXhtml = $outputXhtml;
    }
}
