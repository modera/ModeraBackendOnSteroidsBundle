<?php

namespace Modera\BackendOnSteroidsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('modera_backend_on_steroids');

        $rootNode
            ->children()
                ->arrayNode('compiler')
                    ->addDefaultsIfNotSet()
                    ->children()
                        // these configuration keys are used when .sh scripts are generated:

                        // Where ExtJs workspace with required packages is going to be cloned to.
                        // This is a temporary development directory which is needed only to make Sencha Cmd work properly,
                        // you won't need to deploy it to production (unless you need to compile classes there as well).
                        ->scalarNode('workspace_dir')
                            ->defaultValue('.steroids')
                            ->cannotBeEmpty()
                        ->end()
                        // Name of Sencha Command package that we will use to compile javascript classes
                        ->scalarNode('package_name')
                            ->defaultValue('bundles')
                            ->cannotBeEmpty()
                        ->end()
                        // Where to move a fat javascript file created by Sencha Cmd (usually to make it web accessible),
                        // this file will be created when you use "steroids-compile-bundles.sh"
                        ->scalarNode('output_file')
                            ->defaultValue('web/backend-on-steroids/bundles.js')
                        ->end()
                        // Once MJR's classes are compiled together using "steroids-compile-mjr.sh" where resulting
                        // file should be moved to
                        ->scalarNode('mjr_output_file')
                            ->defaultValue('web/backend-on-steroids/MJR.js')
                        ->end()
                        // List of patterns where modera:backend-on-steroids:copy-classes-to-workspace command
                        // will be looking for ExtJs classes
                        ->arrayNode('path_patterns')
                            ->defaultValue(['@ModeraBackend.*Bundle/Resources/public/js'])
                            ->prototype('scalar')->end()
                        ->end()
                        // List of bundles names with leading @ to exclude from bundles building
                        ->arrayNode('excluded_namespaces')
                            ->defaultValue([])
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
                // By default if the bundle detects that both MJR.js and bundle.js files exist they will be included
                // to backend page with <script> tags, if you don't need to have such behaviour (in dev mode, for example),
                // then set this flag to false
                ->scalarNode('inject_scripts')
                    ->defaultValue(true)
                ->end()
                // This option will be removed in 3.0:
                // Allows to mark blocking assets as non-blocking, you may need to use this option when
                // you have lots of bundles which haven't designated their assets as non-blocking but in fact
                // their are. For example, by writing this configuration all assets which originate from
                // /bundles/moderabackend* directories will be marked as non-blocking (excerpt from app/config/config.yml):
                //
                // modera_backend_on_steroids:
                //     non_blocking_assets_patterns:
                //         - ^/bundles/moderabackend.*
                ->arrayNode('non_blocking_assets_patterns')
                    ->defaultValue([])
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ;

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
