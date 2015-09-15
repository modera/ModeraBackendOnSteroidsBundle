<?php

namespace Modera\BackendOnSteroidsBundle\AssetsHandling;

use Modera\BackendOnSteroidsBundle\DependencyInjection\ModeraBackendOnSteroidsExtension;
use Modera\MjrIntegrationBundle\AssetsHandling\AssetsProvider;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Allows to transform blocking assets to non-blocking.
 *
 * For more details please see {@link Modera\BackendOnSteroidsBundle\DependencyInjection\Configuration}.
 *
 * @author    Sergei Lissovski <sergei.lissovski@modera.org>
 * @copyright 2015 Modera Foundation
 */
class NonBlockingAssetsProvider extends AssetsProvider
{
    /**
     * @var array
     */
    private $nonBlockingAssetsPatterns = [];

    /**
     * @override
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $semanticConfig = $container->getParameter(ModeraBackendOnSteroidsExtension::CONFIG_KEY);

        $this->nonBlockingAssetsPatterns = $semanticConfig['non_blocking_assets_patterns'];
    }

    /**
     * @override
     */
    protected function filterRawAssetsByType($type, array $rawAssets)
    {
        if (count($this->nonBlockingAssetsPatterns) == 0) {
            return parent::filterRawAssetsByType($type, $rawAssets);
        }

        $blockingAssets = [];
        $nonBlockingByPatternMatching = [];

        foreach (parent::filterRawAssetsByType(self::TYPE_BLOCKING, $rawAssets) as $asset) {
            $isMarkedAsNonBlockingByPattern = false;

            foreach ($this->nonBlockingAssetsPatterns as $pattern) {
                if (preg_match('|'.$pattern.'|', $asset)) {
                    $isMarkedAsNonBlockingByPattern = true;

                    break;
                }
            }

            if ($isMarkedAsNonBlockingByPattern) {
                $nonBlockingByPatternMatching[] = $asset;
            } else {
                $blockingAssets[] = $asset;
            }
        }

        if (self::TYPE_BLOCKING  == $type) {
            return $blockingAssets;
        } else { // non-blocking
            return array_merge($nonBlockingByPatternMatching, parent::filterRawAssetsByType($type, $rawAssets));
        }
    }

}