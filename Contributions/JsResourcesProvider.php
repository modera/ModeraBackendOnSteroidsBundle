<?php

namespace Modera\BackendOnSteroidsBundle\Contributions;

use Modera\BackendOnSteroidsBundle\DependencyInjection\ModeraBackendOnSteroidsExtension;
use Sli\ExpanderBundle\Ext\ContributorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * If both MJR.js and bundles.js files exist then they will be contributed.
 *
 * See compiler/mjr_output_file and compiler/output_file configuration properties.
 *
 * @see \Modera\BackendOnSteroidsBundle\DependencyInjection\Configuration
 *
 * @author Sergei Lissovski <sergei.lissovski@gmail.com>
 */
class JsResourcesProvider implements ContributorInterface
{
    /**
     * @var string
     */
    private $kernelDir;

    /**
     * @var array
     */
    private $semanticConfig = array();

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->kernelDir = $container->getParameter('kernel.root_dir');
        $this->semanticConfig = $container->getParameter(ModeraBackendOnSteroidsExtension::CONFIG_KEY);
    }

    /**
     * @inheritDoc
     */
    public function getItems()
    {
        $webDirectoryName = 'web';

        // location to "web" directory in local filesystem, assuming that it is located
        // one level below directory where AppKernel class resides
        $webDir = implode(DIRECTORY_SEPARATOR, [
            $this->kernelDir,
            '..',
            $webDirectoryName
        ]);

        if (!file_exists($webDir)) {
            throw new \RuntimeException(sprintf("Directory '$webDir' doesn't exist!"));
        }

        $filesToContribute = [
            $this->semanticConfig['compiler']['mjr_output_file'],
            $this->semanticConfig['compiler']['output_file']
        ];

        $result = [];

        foreach ($filesToContribute as $path) {
            if (substr($path, 0, strlen($webDirectoryName)) == $webDirectoryName) {
                $pathWithoutWeb = substr($path, strlen($webDirectoryName));
                if (file_exists($webDir . $pathWithoutWeb)) {
                    $result[] = $pathWithoutWeb;
                }
            }
        }

        // bundles.js only make sense when there's MJR.js as well
        if (count($result) == 2) {
            return $result;
        }

        return [];
    }
}