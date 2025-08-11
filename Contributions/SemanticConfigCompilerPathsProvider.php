<?php

namespace Modera\BackendOnSteroidsBundle\Contributions;

use Modera\ExpanderBundle\Ext\ContributorInterface;

/**
 * This implementations uses semantic config's "compiler/path_patterns" configuration parameter
 * to resolve where to look for extjs classes.
 *
 * @see \Modera\BackendOnSteroidsBundle\DependencyInjection\Configuration
 *
 * @copyright 2015 Modera Foundation
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

    public function getItems(): array
    {
        return $this->semanticConfig['compiler']['path_patterns'];
    }

    public static function clazz()
    {
        return get_called_class();
    }
}
