<?php

namespace Modera\BackendOnSteroidsBundle\Tests\Unit\AssetsHandling;

use Modera\BackendOnSteroidsBundle\AssetsHandling\NonBlockingAssetsProvider;
use Modera\BackendOnSteroidsBundle\DependencyInjection\ModeraBackendOnSteroidsExtension;
use Sli\ExpanderBundle\Ext\ContributorInterface;

/**
 * @author    Sergei Lissovski <sergei.lissovski@modera.org>
 * @copyright 2015 Modera Foundation
 */
class NonBlockingAssetsProviderTest extends \PHPUnit_Framework_TestCase
{
    private function createMockProvider($assets)
    {
        $mock = \Phake::mock(ContributorInterface::CLAZZ);
        \Phake::when($mock)->getItems()->thenReturn($assets);

        return $mock;
    }

    private function createIUT(array $nonBlockingAssetsPatterns, array $cssAssets = array(), array $jsAssets = array())
    {
        $container = \Phake::mock('Symfony\Component\DependencyInjection\ContainerInterface');
        \Phake::when($container)
            ->get('modera_mjr_integration.css_resources_provider')
            ->thenReturn($this->createMockProvider($cssAssets))
        ;
        \Phake::when($container)
            ->get('modera_mjr_integration.js_resources_provider')
            ->thenReturn($this->createMockProvider($jsAssets))
        ;
        \Phake::when($container)
            ->getParameter(ModeraBackendOnSteroidsExtension::CONFIG_KEY)
            ->thenReturn(array('non_blocking_assets_patterns' => $nonBlockingAssetsPatterns))
        ;

        return new NonBlockingAssetsProvider($container);
    }

    public function testGetCssAssets()
    {
        // assets that match this pattern will also be returned as non-blocking
        $patterns = [
            '^/bundles/moderabackend.*',
        ];

        $cssAssets = [
            '/bundles/moderabackendtools/originally-i-was-blocking.css',
            '*non-blocking-basterd.css',
            '/bundles/foo/blocking.css',
        ];

        $provider = $this->createIUT($patterns, $cssAssets);

        $blockingAssets = $provider->getCssAssets(NonBlockingAssetsProvider::TYPE_BLOCKING);

        $this->assertEquals(1, count($blockingAssets));
        $this->assertEquals($cssAssets[2], $blockingAssets[0]);

        $nonBlockingAssets = $provider->getCssAssets(NonBlockingAssetsProvider::TYPE_NON_BLOCKING);

        $this->assertEquals(2, count($nonBlockingAssets));
        $this->assertEquals($cssAssets[0], $nonBlockingAssets[0]);
        $this->assertEquals('non-blocking-basterd.css', $nonBlockingAssets[1]);
    }

    public function testGetJavascriptAssets()
    {
        // assets that match this pattern will also be returned as non-blocking
        $patterns = [
            '^/bundles/moderabackend.*',
        ];

        $jsAssets = [
            '/bundles/moderabackendtools/originally-i-was-blocking.js',
            '*non-blocking-basterd.js',
            '/bundles/foo/blocking.js',
        ];

        $provider = $this->createIUT($patterns, [], $jsAssets);

        $blockingAssets = $provider->getJavascriptAssets(NonBlockingAssetsProvider::TYPE_BLOCKING);

        $this->assertEquals(1, count($blockingAssets));
        $this->assertEquals($jsAssets[2], $blockingAssets[0]);

        $nonBlockingAssets = $provider->getJavascriptAssets(NonBlockingAssetsProvider::TYPE_NON_BLOCKING);

        $this->assertEquals(2, count($nonBlockingAssets));
        $this->assertEquals($jsAssets[0], $nonBlockingAssets[0]);
        $this->assertEquals('non-blocking-basterd.js', $nonBlockingAssets[1]);
    }
}
