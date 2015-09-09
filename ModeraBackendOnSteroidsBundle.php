<?php

namespace Modera\BackendOnSteroidsBundle;

use Sli\ExpanderBundle\Ext\ExtensionPoint;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ModeraBackendOnSteroidsBundle extends Bundle
{
    const CONFIG_KEY = 'modera_backend_on_steroids.config';

    // override
    public function build(ContainerBuilder $container)
    {
        $routingResourcesProvider = new ExtensionPoint('modera_backend_on_steroids.extjs_classes');
        $docs = <<<TEXT
This extension point makes your extjs classes visible to "modera:backend-on-steroids:copy-classes-to-workspace"
command. Once you have contributed to this extension point you can use "steroids-compile.sh" script to compile
all extjs classes together.

use Sli\ExpanderBundle\Ext\ContributorInterface;

class ExtjsClassesProvider implements ContributorInterface
{
    /**
     * @inheritDoc
     */
    public function getItems()
    {
        return array(
            '@MyFooBundle/Resources/public/js'
        );
    }
}
TEXT;
        $routingResourcesProvider->setDetailedDescription($docs);
        $routingResourcesProvider->setDescription('Makes your extjs classes visible to "modera:backend-on-steroids:copy-classes-to-workspace"');
        $container->addCompilerPass($routingResourcesProvider->createCompilerPass());
    }
}
