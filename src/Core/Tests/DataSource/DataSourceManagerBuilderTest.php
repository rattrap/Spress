<?php

/*
 * This file is part of the Yosymfony\Spress.
 *
 * (c) YoSymfony <http://github.com/yosymfony>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yosymfony\Spress\Core\Tests\DataSource;

use Yosymfony\Spress\Core\DataSource\DataSourceManagerBuilder;

class DataSourceManagerBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildFromConfigArray()
    {
        $config = [
            'data_source_name_1' => [
                'class' => 'Yosymfony\Spress\Core\DataSource\Filesystem\FilesystemDataSource',
                'arguments' => [
                    'source_root' => '%site_dir%/src',
                ],
            ],
        ];

        $builder = new DataSourceManagerBuilder([
            '%site_dir%' => __dir__.'/../fixtures/project',
        ]);
        $dsm = $builder->buildFromConfigArray($config);

        $this->assertInstanceOf('\Yosymfony\Spress\Core\DataSource\DataSourceManager', $dsm);
        $this->assertContains('data_source_name_1', $dsm->getDataSourceNames());
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Data source "data_source_name_1" class not found: "Acme\AcmeDataSource".
     */
    public function testBuildFromConfigArrayWithNotExistsClass()
    {
        $config = [
            'data_source_name_1' => [
                'class' => 'Acme\AcmeDataSource',
                'arguments' => [
                    'source_root' => __dir__.'/../fixtures/project/src',
                ],
            ],
        ];

        $builder = new DataSourceManagerBuilder();
        $builder->buildFromConfigArray($config);
    }
}
