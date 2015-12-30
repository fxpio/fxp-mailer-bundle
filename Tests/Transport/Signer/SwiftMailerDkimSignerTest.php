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

use Sonatra\Bundle\MailerBundle\Exception\RuntimeException;
use Sonatra\Bundle\MailerBundle\Transport\Signer\SwiftMailerDkimSigner;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Tests for swift mailer DKIM signer.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class SwiftMailerDkimSignerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Filesystem
     */
    protected $fs;

    /**
     * @var string
     */
    protected $cache;

    protected function setUp()
    {
        $this->fs = new Filesystem();
        $this->cache = sys_get_temp_dir().'/sonatra_mailer_bundle_swiftmailer_dkim_signer';
        $this->fs->mkdir($this->cache);
    }

    protected function tearDown()
    {
        $this->fs->remove($this->cache);
    }

    public function testGetName()
    {
        $signer = new SwiftMailerDkimSigner('privateKeyPath', 'domain', 'selector');

        $this->assertSame('swiftmailer_dkim', $signer->getName());
    }

    public function testCreateSignature()
    {
        $path = $this->cache.'/private_key';
        $this->fs->dumpFile($path, 'CONTENT');

        $signer = new SwiftMailerDkimSigner($path, 'domain', 'selector');

        $signature = $signer->createSignature();

        $this->assertInstanceOf(\Swift_Signers_DKIMSigner::class, $signature);
    }

    public function testCreateSignatureWithUnreadablePrivateKey()
    {
        $path = $this->cache.'/private_key';

        $msg = 'The private key path of the DKIM swiftmailer signer "%s" does not exist or is not readable';
        $this->setExpectedException(RuntimeException::class, sprintf($msg, $path));

        $signer = new SwiftMailerDkimSigner($path, 'domain', 'selector');

        $signer->createSignature();
    }

    public function testCreateSignatureWithInvalidPrivateKey()
    {
        $path = $this->cache.'/';

        $msg = 'Impossible to read the private key of the DKIM swiftmailer signer "%s"';
        $this->setExpectedException(RuntimeException::class, sprintf($msg, $path));

        $signer = new SwiftMailerDkimSigner($path, 'domain', 'selector');

        $signer->createSignature();
    }
}
