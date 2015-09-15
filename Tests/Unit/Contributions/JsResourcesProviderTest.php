<?php

namespace Modera\BackendOnSteroidsBundle\Tests\Unit\Contributions;

use Modera\BackendOnSteroidsBundle\Contributions\JsResourcesProvider;
use Modera\BackendOnSteroidsBundle\DependencyInjection\ModeraBackendOnSteroidsExtension;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author    Sergei Lissovski <sergei.lissovski@modera.org>
 * @copyright 2015 Modera Foundation
 */
class JsResourcesProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    static private $webPath;

    public static function setUpBeforeClass()
    {
        self::$webPath = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', '..', 'Fixtures', 'App', 'app', 'web']);
    }

    public static function tearDownAfterClass()
    {
        $fs = new Filesystem();

        if ($fs->exists(self::$webPath)) {
            $fs->remove(self::$webPath);
        }
    }


    private function createMockContainer(array $compilerConfig)
    {
        $container = \Phake::mock('Symfony\Component\DependencyInjection\ContainerInterface');
        \Phake::when($container)
            ->getParameter('kernel.root_dir')
            ->thenReturn(self::$webPath)
        ;
        \Phake::when($container)
            ->getParameter(ModeraBackendOnSteroidsExtension::CONFIG_KEY)
            ->thenReturn(array(
                'compiler' => $compilerConfig
            ))
        ;

        return $container;
    }

    private function createIUT()
    {
        $container = $this->createMockContainer(array(
            'mjr_output_file' => 'web/backend-on-steroids/MJR.js',
            'output_file' => 'web/backend-on-steroids/bundles.js'
        ));

        return new JsResourcesProvider($container);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testGetItemsWhenWebDirectoryDoesNotExist()
    {
        $provider = $this->createIUT();

        $resources = $provider->getItems();

        $this->assertEquals(0, count($resources));
    }

    private function createDirsIfNeeded()
    {
        if (!file_exists(self::$webPath)) {
            mkdir(self::$webPath);
            mkdir(implode(DIRECTORY_SEPARATOR, [self::$webPath, 'backend-on-steroids']));
        }
    }

    public function testGetItemsWithNeitherOfAssetsExist()
    {
        $this->createDirsIfNeeded();

        $provider = $this->createIUT();

        $resources = $provider->getItems();

        $this->assertEquals(0, count($resources));
    }

    private function createAsset($name)
    {
        $assetPath = implode(DIRECTORY_SEPARATOR, [self::$webPath, 'backend-on-steroids', $name]);
        file_put_contents($assetPath, 'foojs');
    }

    public function testGetItemsWhenOneAssetExists()
    {
        $this->createDirsIfNeeded();

        $this->createAsset('MJR.js');

        $provider = $this->createIUT();

        $resources = $provider->getItems();

        $this->assertEquals(0, count($resources));
    }

    public function testGetItemsWhenBothAssetsExist()
    {
        $this->createDirsIfNeeded();

        $this->createAsset('MJR.js');
        $this->createAsset('bundles.js');

        $provider = $this->createIUT();

        $resources = $provider->getItems();

        $this->assertEquals(2, count($resources));

        $this->assertEquals('/backend-on-steroids/MJR.js', $resources[0]);
        $this->assertEquals('/backend-on-steroids/bundles.js', $resources[1]);
    }
}