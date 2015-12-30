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

use Sonatra\Bundle\MailerBundle\Exception\InvalidArgumentException;
use Sonatra\Bundle\MailerBundle\MailTypes;
use Sonatra\Bundle\MailerBundle\Transport\TransportInterface;

/**
 * The mailer.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class Mailer implements MailerInterface
{
    /**
     * @var MailTemplaterInterface
     */
    protected $templater;

    /**
     * @var TransportInterface[]
     */
    protected $transports;

    /**
     * @param MailTemplaterInterface $templater  The mail templater
     * @param TransportInterface[]   $transports The transports
     */
    public function __construct(MailTemplaterInterface $templater, array $transports)
    {
        $this->templater = $templater;

        foreach ($transports as $transport) {
            $this->addTransport($transport);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addTransport(TransportInterface $transport)
    {
        $this->transports[$transport->getName()] = $transport;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasTransport($name)
    {
        return isset($this->transports[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getTransport($name)
    {
        if (!isset($this->transports[$name])) {
            $msg = sprintf('The "%s" transport does not exist', $name);
            throw new InvalidArgumentException($msg);
        }

        return $this->transports[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function send($transport, $message, $template = null, array $variables = array(), $type = MailTypes::TYPE_ALL)
    {
        $transport = $this->getTransport($transport);
        $mailRendered = null !== $template
            ? $this->templater->render($template, $variables, $type)
            : null;

        return $transport->send($message, $mailRendered);
    }
}
