<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Tests\Transport\Signer;

use Sonatra\Bundle\MailerBundle\Exception\InvalidArgumentException;
use Sonatra\Bundle\MailerBundle\Transport\Signer\SignerInterface;
use Sonatra\Bundle\MailerBundle\Transport\Signer\SignerRegistry;

/**
 * Tests for signer registry.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class SignerRegistryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSigner()
    {
        $signer = $this->getMock(SignerInterface::class);
        $signer->expects($this->exactly(1))
            ->method('getName')
            ->will($this->returnValue('test'));

        $registry = new SignerRegistry(array($signer));

        $this->assertTrue($registry->hasSigner('test'));
        $this->assertSame($signer, $registry->getSigner('test'));
    }

    public function testGetUnknownSigner()
    {
        $msg = 'The "test" signer does not exist';
        $this->setExpectedException(InvalidArgumentException::class, $msg);

        $registry = new SignerRegistry(array());

        $registry->getSigner('test');
    }
}
