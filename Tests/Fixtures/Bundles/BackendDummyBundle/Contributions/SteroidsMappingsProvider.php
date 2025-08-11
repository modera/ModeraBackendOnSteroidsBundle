<?php

namespace Modera\BackendOnSteroidsBundle\Tests\Fixtures\Bundles\BackendDummyBundle\Contributions;

use Modera\ExpanderBundle\Ext\ContributorInterface;

class SteroidsMappingsProvider implements ContributorInterface
{
    public function getItems(): array
    {
        return array(
            '@ModeraBackendDummyBundle/Resources/public/js',
        );
    }
}
