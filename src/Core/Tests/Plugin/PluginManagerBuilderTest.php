<?php

/*
 * This file is part of the Yosymfony\Spress.
 *
 * (c) YoSymfony <http://github.com/yosymfony>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yosymfony\Spress\Core\Tests\Plugin;

use Dflydev\EmbeddedComposer\Core\EmbeddedComposerBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Yosymfony\Spress\Core\Plugin\PluginManagerBuilder;

class PluginManagerBuilderTest extends \PHPUnit_Framework_TestCase
{
    protected $pluginDir;
    protected $embeddedComposer;

    public function setUp()
    {
        $this->pluginDir = __DIR__.'/../fixtures/project/src/plugins';
        $vendorDir = __DIR__.'/../fixtures/project/vendor';

        $autoloaders = spl_autoload_functions();

        $embeddedComposerBuilder = new EmbeddedComposerBuilder($autoloaders[0][0]);
        $this->embeddedComposer = $embeddedComposerBuilder
            ->setComposerFilename('composer.json')
            ->setVendorDirectory($vendorDir)
            ->build();
        $this->embeddedComposer->processAdditionalAutoloads();
    }

    public function testBuild()
    {
        $builder = new PluginManagerBuilder($this->pluginDir, new EventDispatcher());
        $pm = $builder->build();
        $pluginCollection = $pm->getPluginCollection();

        $this->assertCount(2, $pluginCollection);

        $plugin = $pluginCollection->get('Test plugin');

        $metas = $plugin->getMetas();

        $this->assertEquals('Test plugin', $metas['name']);

        $plugin = $pluginCollection->get('Hello plugin');

        $metas = $plugin->getMetas();

        $this->assertEquals('Hello plugin', $metas['name']);
    }
}
