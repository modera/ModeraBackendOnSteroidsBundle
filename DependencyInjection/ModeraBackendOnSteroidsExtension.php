<?php

namespace Modera\BackendOnSteroidsBundle\DependencyInjection;

use Modera\BackendOnSteroidsBundle\Contributions\JsResourcesProvider;
use Modera\BackendOnSteroidsBundle\Contributions\SemanticConfigCompilerPathsProvider;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ModeraBackendOnSteroidsExtension extends Extension
{
    const CONFIG_KEY = 'modera_backend_on_steroids.config';

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter(self::CONFIG_KEY, $config);

        if ($config['inject_scripts']) {
            $jsResourcesProvider = new Definition(JsResourcesProvider::clazz());
            $jsResourcesProvider->addArgument(new Reference('service_container'));
            $jsResourcesProvider->addTag('modera_mjr_integration.js_resources_provider');

            $container->setDefinition(
                'modera_backend_on_steroids.contributions.js_resources_provider',
                $jsResourcesProvider
            );
        }

        $semanticPathsProvider = new Definition(SemanticConfigCompilerPathsProvider::clazz());
        $semanticPathsProvider->addArgument($config);
        $semanticPathsProvider->addTag('modera_backend_on_steroids.extjs_classes_paths_provider');

        $container->setDefinition('modera_backend_on_steroids.semantic_config_classes_paths_provider', $semanticPathsProvider);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
}
