<?php

namespace Modera\BackendOnSteroidsBundle\Contributions;

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
    private $semanticConfig;
    private $kernel;

    /**
     * @param array $semanticConfig
     * @param KernelInterface $kernel
     */
    public function __construct(array $semanticConfig, KernelInterface $kernel)
    {
        $this->semanticConfig = $semanticConfig;
        $this->kernel = $kernel;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        $matchedPaths = [];

        foreach ($this->semanticConfig['compiler']['path_patterns'] as $pattern) {
            if (substr($pattern, 0, 1) == '@') { // bundle's presence indicated as "@" in path
                $bundleName = null;

                $separatorIndex = strpos($pattern, '/');
                // 1 as start index because we don't need @
                if (false !== $separatorIndex) {
                    $bundleName = substr($pattern, 1, $separatorIndex - 1);
                } else { // the whole path is just a bundle name
                    $bundleName = substr($pattern, 1);
                }

                // @ModeraBackend.*Bundle/Resources/public/js
                // ModeraBackend.*Bundle

                /* @var BundleInterface[] $matchedBundles */
                $matchedBundles = [];
                foreach ($this->kernel->getBundles() as $bundle) {
                    /* @var BundleInterface $bundle */

                    $regex = '|^' . $bundleName . '$|';

                    if (preg_match($regex, $bundle->getName())) {
                        $matchedBundles[] = $bundle;
                    }
                }

                foreach ($matchedBundles as $matchedBundle) {
                    $compiledPath = null;

                    if (false !== $separatorIndex) {
                        // @ModeraBackend.*Bundle/Resources/public/js -->
                        // /var/www/mymegaproject/PathOfBackendBlahBundle/Resources/public/js
                        $compiledPath = implode('', [
                            $matchedBundle->getPath(),
                            substr($pattern, $separatorIndex),
                        ]);
                    } else { // the whole bundle
                        $compiledPath = $matchedBundle->getPath();
                    }

                    $matchedPaths[] = $compiledPath;
                }
            } else {
                $matchedPaths[] = $pattern;
            }
        }

        return $matchedPaths;
    }

    static public function clazz()
    {
        return get_called_class();
    }
}