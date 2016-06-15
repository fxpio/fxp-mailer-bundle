<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Tests\Loader;

use Sonatra\Bundle\MailerBundle\Loader\ConfigLayoutLoader;
use Sonatra\Bundle\MailerBundle\Model\LayoutInterface;

/**
 * Tests for config layout loader.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ConfigLayoutLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testLoad()
    {
        $template = array(
            'name' => 'test',
            'label' => 'Test',
            'description' => 'Description of test',
            'enabled' => true,
            'body' => 'Content of layout with {{ twig_variable }}.',
            'translations' => array(
                array(
                    'locale' => 'fr',
                    'label' => 'Test fr',
                    'description' => 'Description du test',
                    'body' => 'Contenu du layout avec {{ twig_variable }}.',
                ),
            ),
        );

        $loader = new ConfigLayoutLoader(array($template));

        $this->assertInstanceOf(LayoutInterface::class, $loader->load('test'));
    }

    /**
     * @expectedException \Sonatra\Bundle\MailerBundle\Exception\UnknownLayoutException
     * @€@expectedExceptionMessage The "test" layout template does not exist
     */
    public function testLoadUnknownTemplate()
    {
        $loader = new ConfigLayoutLoader(array());

        $loader->load('test');
    }
}
