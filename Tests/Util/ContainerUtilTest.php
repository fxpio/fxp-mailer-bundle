<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Bundle\MailerBundle\Tests\Util;

use Fxp\Bundle\MailerBundle\FxpMailerBundle;
use Fxp\Bundle\MailerBundle\Util\ContainerUtil;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

/**
 * Tests for container util.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 *
 * @internal
 */
final class ContainerUtilTest extends TestCase
{
    public function testGetRealFile(): void
    {
        $realPath = realpath(__DIR__.'/../../Resources/config/mailer.xml');
        $file = '@FxpMailerBundle/Resources/config/mailer.xml';
        $file = ContainerUtil::getRealFile($this->getContainer(), $file);

        $this->assertSame($realPath, realpath($file));
    }

    /**
     * Gets the container.
     *
     * @return ContainerBuilder
     */
    protected function getContainer()
    {
        return new ContainerBuilder(new ParameterBag([
            'kernel.bundles' => [
                'FxpMailerBundle' => FxpMailerBundle::class,
            ],
        ]));
    }
}
