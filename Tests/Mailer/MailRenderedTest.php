<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Tests\Mailer;

use Sonatra\Bundle\MailerBundle\Mailer\MailRendered;
use Sonatra\Bundle\MailerBundle\Model\MailInterface;

/**
 * Tests for mail rendered.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class MailRenderedTest extends \PHPUnit_Framework_TestCase
{
    public function testModel()
    {
        /* @var MailInterface $template */
        $template = $this->getMock(MailInterface::class);
        $subject = 'Subject of mail';
        $htmlBody = 'HTML body of mail';
        $body = 'Body of mail';

        $rendered = new MailRendered($template, $subject, $htmlBody, $body);

        $this->assertSame($template, $rendered->getTemplate());
        $this->assertSame($subject, $rendered->getSubject());
        $this->assertSame($htmlBody, $rendered->getHtmlBody());
        $this->assertSame($body, $rendered->getBody());

        $subject2 = 'Subject of mail 2';
        $htmlBody2 = 'HTML body of mail 2';
        $body2 = 'Body of mail 2';

        $rendered->setSubject($subject2);
        $rendered->setHtmlBody($htmlBody2);
        $rendered->setBody($body2);

        $this->assertSame($template, $rendered->getTemplate());
        $this->assertSame($subject2, $rendered->getSubject());
        $this->assertSame($htmlBody2, $rendered->getHtmlBody());
        $this->assertSame($body2, $rendered->getBody());
    }
}
