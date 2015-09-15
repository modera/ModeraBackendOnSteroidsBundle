<?php

namespace Modera\BackendOnSteroidsBundle\Tests\Fixtures\Bundles\BackendDummyBundle;

use Modera\BackendOnSteroidsBundle\Tests\Fixtures\Bundles\BackendDummyBundle\DependencyInjection\ModeraBackendDummyExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Sergei Lissovski <sergei.lissovski@gmail.com>
 */
class ModeraBackendDummyBundle extends Bundle
{
    // override
    public function build(ContainerBuilder $container)
    {
        $container->registerExtension(new ModeraBackendDummyExtension());
    }
}
