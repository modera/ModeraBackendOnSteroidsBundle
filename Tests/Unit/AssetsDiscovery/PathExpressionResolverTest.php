<?php

namespace Modera\BackendOnSteroidsBundle\Tests\Unit\AssetsDiscovery;

use Modera\BackendOnSteroidsBundle\AssetsDiscovery\PathExpressionResolver;

/**
 * @author    Sergei Lissovski <sergei.lissovski@modera.org>
 * @copyright 2015 Modera Foundation
 */
class PathExpressionResolverTest extends \PHPUnit_Framework_TestCase
{
    private function createBundle($name, $path)
    {
        $bundle = \Phake::mock('Symfony\Component\HttpKernel\Bundle\BundleInterface');
        \Phake::when($bundle)->getName()->thenReturn($name);
        \Phake::when($bundle)->getPath()->thenReturn($path);

        return $bundle;
    }

    public function testResolve()
    {
        $kernel = \Phake::mock('Symfony\Component\HttpKernel\KernelInterface');
        \Phake::when($kernel)->getBundles()->thenReturn([
            $this->createBundle('ModeraBackendFooBundle', '/var/www/myapp/vendor/modera/backend-foo'),
            $this->createBundle('ModeraBackenFooBundle', '/var/www/myapp/vendor/modera/backen-foo'), // nein
            $this->createBundle('PartnerBackendFooBundle', '/var/www/myapp/vendor/partner/backend-foo'),
        ]);

        $resolver = new PathExpressionResolver($kernel);

        $paths = $resolver->resolve('@ModeraBackend.*Bundle/Resources/public/js');

        $this->assertEquals(1, count($paths));
        $this->assertEquals('/var/www/myapp/vendor/modera/backend-foo/Resources/public/js', $paths[0]);

        $paths = $resolver->resolve('@PartnerBackend.*Bundle/Resources/public/js/runtime');

        $this->assertEquals(1, count($paths));
        $this->assertEquals('/var/www/myapp/vendor/partner/backend-foo/Resources/public/js/runtime', $paths[0]);
    }
}
