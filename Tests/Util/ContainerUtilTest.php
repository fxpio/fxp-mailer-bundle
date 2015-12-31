<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Tests\Util;

use Sonatra\Bundle\MailerBundle\SonatraMailerBundle;
use Sonatra\Bundle\MailerBundle\Util\ContainerUtil;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

/**
 * Tests for container util.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ContainerUtilTest extends \PHPUnit_Framework_TestCase
{
    public function testGetRealFile()
    {
        $realPath = realpath(__DIR__.'/../../Resources/config/mailer.xml');
        $file = '@SonatraMailerBundle/Resources/config/mailer.xml';
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
        $container = new ContainerBuilder(new ParameterBag(array(
            'kernel.bundles' => array(
                'SonatraMailerBundle' => SonatraMailerBundle::class,
            ),
        )));

        return $container;
    }
}
