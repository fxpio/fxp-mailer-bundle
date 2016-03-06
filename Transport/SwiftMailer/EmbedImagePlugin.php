<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Transport\SwiftMailer;

use Sonatra\Bundle\MailerBundle\Util\EmbedImageUtil;

/**
 * SwiftMailer Embed Image Plugin.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class EmbedImagePlugin extends AbstractPlugin
{
    /**
     * @var string
     */
    protected $webDir;

    /**
     * @var string
     */
    protected $hostPattern;

    /**
     * Constructor.
     *
     * @param string|null $webDir      The web directory
     * @param string      $hostPattern The pattern of allowed host
     */
    public function __construct($webDir = null, $hostPattern = '/(.*)+/')
    {
        $this->webDir = (string) $webDir;
        $this->hostPattern = $hostPattern;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSendPerformed(\Swift_Events_SendEvent $event)
    {
        $message = $event->getMessage();

        if (!$this->isEnabled() || !$message instanceof \Swift_Message
                || in_array($message->getId(), $this->performed) || null === $message->getBody()) {
            return;
        }

        $dom = new \DOMDocument('1.0', 'utf-8');
        $dom->loadHTML($message->getBody());
        $xpath = new \DOMXPath($dom);
        $nodes = $xpath->query('//img/@src');
        $images = array();

        foreach ($nodes as $node) {
            $this->embedImage($message, $node, $images);
        }

        $message->setBody($dom->saveHTML(), 'text/html');
        $this->performed[] = $message->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function sendPerformed(\Swift_Events_SendEvent $event)
    {
        // not used
    }

    /**
     * Embed the image in message and replace the image link by attachment id.
     *
     * @param \Swift_Message $message The swift mailer message
     * @param \DOMAttr       $node    The dom attribute of image
     * @param array          $images  The map of image ids passed by reference
     */
    protected function embedImage(\Swift_Message $message, \DOMAttr $node, array &$images)
    {
        if (0 === strpos($node->nodeValue, 'cid:')) {
            return;
        }

        if (isset($images[$node->nodeValue])) {
            $node->nodeValue = $images[$node->nodeValue];
        } else {
            $node->nodeValue = EmbedImageUtil::getLocalPath($node->nodeValue, $this->webDir, $this->hostPattern);
            $cid = $message->embed(\Swift_Image::fromPath($node->nodeValue));
            $images[$node->nodeValue] = $cid;
            $node->nodeValue = $cid;
        }
    }
}
