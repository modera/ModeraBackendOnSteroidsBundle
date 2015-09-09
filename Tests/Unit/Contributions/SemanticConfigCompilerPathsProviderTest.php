<?php

namespace Modera\BackendOnSteroidsBundle\Tests\Unit\Contributions;

use Modera\BackendOnSteroidsBundle\Contributions\SemanticConfigCompilerPathsProvider;

/**
 * @author Sergei Lissovski <sergei.lissovski@gmail.com>
 */
class SemanticConfigCompilerPathsProviderTest extends \PHPUnit_Framework_TestCase
{
    private function createBundle($name, $path)
    {
        $bundle = \Phake::mock('Symfony\Component\HttpKernel\Bundle\BundleInterface');
        \Phake::when($bundle)->getName()->thenReturn($name);
        \Phake::when($bundle)->getPath()->thenReturn($path);

        return $bundle;
    }

    public function testGetPaths()
    {
        $semanticConfig = array(
            'compiler' => array(
                'path_patterns' => array(
                    '@ModeraBackend.*Bundle/Resources/public/js',
                    '@PartnerBackend.*Bundle/Resources/public/js/runtime',
                )
            )
        );

        $kernel = \Phake::mock('Symfony\Component\HttpKernel\KernelInterface');
        \Phake::when($kernel)->getBundles()->thenReturn([
            $this->createBundle('ModeraBackendFooBundle', '/var/www/myapp/vendor/modera/backend-foo'),
            $this->createBundle('ModeraBackenFooBundle', '/var/www/myapp/vendor/modera/backen-foo'), // nein
            $this->createBundle('PartnerBackendFooBundle', '/var/www/myapp/vendor/partner/backend-foo')
        ]);

        $resolver = new SemanticConfigCompilerPathsProvider($semanticConfig, $kernel);

        $paths = $resolver->getItems();

        $this->assertEquals(2, count($paths));
        $this->assertEquals('/var/www/myapp/vendor/modera/backend-foo/Resources/public/js', $paths[0]);
        $this->assertEquals('/var/www/myapp/vendor/partner/backend-foo/Resources/public/js/runtime', $paths[1]);
    }
}