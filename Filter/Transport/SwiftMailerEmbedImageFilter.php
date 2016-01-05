<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Filter\Transport;

use Sonatra\Bundle\MailerBundle\Filter\TransportFilterInterface;
use Sonatra\Bundle\MailerBundle\Mailer\MailRenderedInterface;

/**
 * Filter for replace image link by embed image.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class SwiftMailerEmbedImageFilter implements TransportFilterInterface
{
    /**
     * {@inheritdoc}
     *
     * @param \Swift_Message $message The swift mailer message
     */
    public function filter($transport, $message, MailRenderedInterface $mailRendered = null)
    {
        if (!$message instanceof \Swift_Message
                || null === $mailRendered
                || null === $mailRendered->getHtmlBody()) {
            return;
        }

        $html = $mailRendered->getHtmlBody();
        $dom = new \DOMDocument('1.0', 'utf-8');
        $dom->loadHTML($html);
        $xpath = new \DOMXPath($dom);
        $nodes = $xpath->query('//img/@src');

        foreach ($nodes as $node) {
            $this->embedImage($message, $node);
        }

        $mailRendered->setHtmlBody($dom->saveHTML());
    }

    /**
     * {@inheritdoc}
     */
    public function supports($transport, $message, MailRenderedInterface $mailRendered = null)
    {
        return 'swiftmailer' === $transport
            && $message instanceof \Swift_Message
            && null !== $mailRendered;
    }

    /**
     * Embed the image in message and replace the image link by attachment id.
     *
     * @param \Swift_Message $message The swift mailer message
     * @param \DOMAttr       $node    The dom attribute of image
     */
    protected function embedImage(\Swift_Message $message, \DOMAttr $node)
    {
        $cid = $message->embed(\Swift_Image::fromPath($node->nodeValue));
        $node->nodeValue = $cid;
    }
}
