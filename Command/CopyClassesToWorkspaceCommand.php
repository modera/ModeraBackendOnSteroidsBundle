<?php

namespace Modera\BackendOnSteroidsBundle\Command;

use Modera\BackendOnSteroidsBundle\DependencyInjection\ModeraBackendOnSteroidsExtension;
use Modera\BackendOnSteroidsBundle\AssetsDiscovery\PathExpressionResolver;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Finder\Finder;

/**
 * @author Sergei Lissovski <sergei.lissovski@gmail.com>
 */
class CopyClassesToWorkspaceCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('modera:backend-on-steroids:copy-classes-to-workspace')
            ->setDescription('Copies contributed ExtJs classes to workspace\'s directory so you can later compile them')
            ->addOption('track-progress', null, null, 'If provided then you will see where from and to javascript files are copied.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $trackProgress = $input->getOption('track-progress');

        $config = $this->getContainer()->getParameter(ModeraBackendOnSteroidsExtension::CONFIG_KEY)['compiler'];

        $filesystem = $this->getContainer()->get('modera_backend_on_steroids.filesystem');
        $provider = $this->getContainer()->get('modera_backend_on_steroids.extjs_classes_paths_provider');
        /* @var PathExpressionResolver $pathExpressionsResolver */
        $pathExpressionsResolver = $this->getContainer()->get('modera_mjr_integration.assets_descovery.path_expression_resolver');
        $finder = new Finder();

        $paths = [];
        foreach ($provider->getItems() as $rawPath) {
            $paths = array_merge($paths, $pathExpressionsResolver->resolve($rawPath));
        }
        $paths = array_unique($paths);

        $hostDir = implode(DIRECTORY_SEPARATOR, [
            getcwd(), $config['workspace_dir'], 'packages', $config['package_name'], 'src',
        ]);

        $skippedFiles = [];
        $copiedFiles = [];

        foreach ($paths as $jsPath) {
            if ($filesystem->exists($jsPath)) {
                foreach ($finder->in($jsPath)->name('*.js') as $filename => $file) {
                    /* @var SplFileInfo $file */

                    $contents = file_get_contents($filename);

                    $regex = '/Ext\.define\s*\(\s*["\']{1}(.*)["\']\s*,/';

                    if (preg_match($regex, $contents, $matches)) {
                        $className = $matches[1];

                        $namespace = explode('.', $className);
                        array_pop($namespace);

                        $namespaceToPath = implode(DIRECTORY_SEPARATOR, $namespace);

                        $targetDir = $hostDir.DIRECTORY_SEPARATOR.$namespaceToPath;

                        if (!$filesystem->exists($targetDir)) {
                            $filesystem->mkdir($targetDir);
                        }

                        $newFilepath = $targetDir.DIRECTORY_SEPARATOR.$file->getFilename();

                        $copiedFiles[] = array(
                            'source' => $filename,
                            'target' => $newFilepath,
                        );

                        if ($trackProgress) {
                            $output->writeln(' '.$filename);
                            $output->writeln(' copied to');
                            $output->writeln(' '.$newFilepath);
                            $output->writeln('');
                        }

                        $filesystem->copy($filename, $newFilepath);
                    } else {
                        $skippedFiles[] = $filename;
                    }
                }
            }
        }

        $output->writeln(sprintf(
            ' <info>Done! In total %d files were copied to "%s" directory.</info>', count($copiedFiles), $hostDir
        ));
        $output->writeln(' <info>Now you case run this to have them compiled for you (on host machine, outside of docker container):</info>');
        $output->writeln(' ./steroids-compile-bundles.sh');
        if ($skippedFiles) {
            $output->writeln(
                sprintf(' <comment>%d files were skipped because they seem to contain no Extjs class: </comment>', count($skippedFiles))
            );
            foreach ($skippedFiles as $filename) {
                $output->writeln(' - '.$filename);
            }
        }

        return 0;
    }
}
