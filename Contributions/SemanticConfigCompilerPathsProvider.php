<?php

namespace Modera\BackendOnSteroidsBundle\Contributions;

use Modera\BackendOnSteroidsBundle\AssetsDiscovery\PathExpressionResolver;
use Sli\ExpanderBundle\Ext\ContributorInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * This implementations uses semantic config's "compiler/path_patterns" configuration parameter
 * to resolve where to look for extjs classes.
 *
 * @see \Modera\BackendOnSteroidsBundle\DependencyInjection\Configuration
 *
 * @author Sergei Lissovski <sergei.lissovski@gmail.com>
 */
class SemanticConfigCompilerPathsProvider implements ContributorInterface
{
    /**
     * @var array
     */
    private $semanticConfig;

    /**
     * @param array $semanticConfig
     */
    public function __construct(array $semanticConfig)
    {
        $this->semanticConfig = $semanticConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function getItems()
    {
        return $this->semanticConfig['compiler']['path_patterns'];
    }

    static public function clazz()
    {
        return get_called_class();
    }
}