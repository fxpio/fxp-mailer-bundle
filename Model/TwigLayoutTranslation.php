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

use Sonatra\Bundle\MailerBundle\Exception\InvalidArgumentException;
use Sonatra\Bundle\MailerBundle\Model\Traits\FileTrait;

/**
 * Model for twig file layout translation.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class TwigLayoutTranslation extends LayoutTranslation implements TwigTemplateInterface
{
    use FileTrait;

    /**
     * Constructor.
     *
     * @param LayoutInterface $layout The layout
     * @param string|null     $file   The file name
     */
    public function __construct(LayoutInterface $layout, $file = null)
    {
        parent::__construct($layout);

        $this->setFile($file);
        $this->body = 'body';
    }

    /**
     * {@inheritdoc}
     */
    protected function support($file)
    {
        if (null !== $file && (!is_file($file) || 'twig' !== pathinfo($file, PATHINFO_EXTENSION))) {
            $msg = 'The "%s" file is not supported by the layout translation file template';
            throw new InvalidArgumentException(sprintf($msg, $file));
        }
    }
}
