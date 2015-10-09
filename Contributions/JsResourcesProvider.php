<?php

namespace Modera\BackendOnSteroidsBundle\Contributions;

use Modera\BackendOnSteroidsBundle\DependencyInjection\ModeraBackendOnSteroidsExtension;
use Sli\ExpanderBundle\Ext\ContributorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * If both MJR.js and bundles.js files exist then they will be contributed. When files are contributed their
 * last modification time is added as a suffix, this makes it possible to invalidate browser's cache without
 * extra work.
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
     * {@inheritdoc}
     */
    public function getItems()
    {
        $webDirectoryName = 'web';

        // location to "web" directory in local filesystem, assuming that it is located
        // one level below directory where AppKernel class resides
        $webDir = implode(DIRECTORY_SEPARATOR, [
            $this->kernelDir,
            '..',
            $webDirectoryName,
        ]);

        if (!file_exists($webDir)) {
            throw new \RuntimeException(sprintf("Directory '$webDir' doesn't exist!"));
        }

        $filesToContribute = [
            $this->semanticConfig['compiler']['mjr_output_file'],
            $this->semanticConfig['compiler']['output_file'],
        ];

        $result = [];

        foreach ($filesToContribute as $path) {
            if (substr($path, 0, strlen($webDirectoryName)) == $webDirectoryName) {
                $pathWithoutWeb = substr($path, strlen($webDirectoryName));
                $pathname = $webDir.$pathWithoutWeb;

                if (file_exists($pathname)) {
                    $lastModificationTimestamp = filemtime($pathname);
                    if (!$lastModificationTimestamp) {
                        throw new \RuntimeException('Unable to get last modification time for file '.$pathname);
                    }

                    // MPFE-782
                    // In case user's browser has cached files and server is configured that it doesn't
                    // issue meta-request to check last modification time, then this is going to help
                    // to invalidate browser's cache
                    $result[] = $pathWithoutWeb.'?'.$lastModificationTimestamp;
                }
            }
        }

        // bundles.js only make sense when there's MJR.js as well
        if (count($result) == 2) {
            return $result;
        }

        return [];
    }

    /**
     * @return string
     */
    public static function clazz()
    {
        return get_called_class();
    }
}
