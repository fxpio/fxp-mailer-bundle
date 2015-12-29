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
        $this->assertSame('Subject of mail', $rendered->getSubject());
        $this->assertSame('HTML body of mail', $rendered->getHtmlBody());
        $this->assertSame('Body of mail', $rendered->getBody());
    }
}
