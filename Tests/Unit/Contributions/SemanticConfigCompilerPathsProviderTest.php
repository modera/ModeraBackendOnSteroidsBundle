<?php

namespace Modera\BackendOnSteroidsBundle\Tests\Unit\Contributions;

use Modera\BackendOnSteroidsBundle\Contributions\SemanticConfigCompilerPathsProvider;

/**
 * @author Sergei Lissovski <sergei.lissovski@gmail.com>
 */
class SemanticConfigCompilerPathsProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testGetPaths()
    {
        $semanticConfig = array(
            'compiler' => array(
                'path_patterns' => array(
                    '@ModeraBackend.*Bundle/Resources/public/js',
                    '@PartnerBackend.*Bundle/Resources/public/js/runtime',
                ),
            ),
        );

        $provider = new SemanticConfigCompilerPathsProvider($semanticConfig);

        $result = $provider->getItems();

        $this->assertEquals($semanticConfig['compiler']['path_patterns'], $result);
    }
}
