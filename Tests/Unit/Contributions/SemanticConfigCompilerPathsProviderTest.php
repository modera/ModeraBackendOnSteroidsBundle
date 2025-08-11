<?php

namespace Modera\BackendOnSteroidsBundle\Tests\Unit\Contributions;

use Modera\BackendOnSteroidsBundle\Contributions\SemanticConfigCompilerPathsProvider;

class SemanticConfigCompilerPathsProviderTest extends \PHPUnit\Framework\TestCase
{
    public function testGetPaths(): void
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
