<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Loader;

use Sonatra\Bundle\MailerBundle\Exception\UnknownMailException;
use Sonatra\Bundle\MailerBundle\MailTypes;
use Sonatra\Bundle\MailerBundle\Model\MailInterface;
use Sonatra\Bundle\MailerBundle\Util\MailUtil;

/**
 * Array mail loader.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ArrayMailLoader implements MailLoaderInterface
{
    /**
     * @var MailInterface[]
     */
    protected $mails;

    /**
     * Constructor.
     *
     * @param MailInterface[] $mails The mail template
     */
    public function __construct(array $mails)
    {
        $this->mails = array();

        foreach ($mails as $mail) {
            $this->addMail($mail);
        }
    }

    /**
     * Add the mail template.
     *
     * @param MailInterface $mail The mail template
     */
    public function addMail(MailInterface $mail)
    {
        $this->mails[$mail->getName()] = $mail;
    }

    /**
     * {@inheritdoc}
     */
    public function load($name, $type = MailTypes::TYPE_ALL)
    {
        if (isset($this->mails[$name]) && MailUtil::isValid($this->mails[$name], $type)) {
            return $this->mails[$name];
        }

        throw new UnknownMailException($name, $type);
    }
}
