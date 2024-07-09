<?php

namespace Modera\BackendOnSteroidsBundle\Tests\Fixtures\Bundles\BackendDummyBundle\Contributions;

use Sli\ExpanderBundle\Ext\ContributorInterface;

/**
 * @author Sergei Lissovski <sergei.lissovski@gmail.com>
 */
class SteroidsMappingsProvider implements ContributorInterface
{
    public function getItems(): array
    {
        return array(
            '@ModeraBackendDummyBundle/Resources/public/js',
        );
    }
}
