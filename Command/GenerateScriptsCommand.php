<?php

namespace Modera\BackendOnSteroidsBundle\Command;

use Modera\BackendOnSteroidsBundle\DependencyInjection\ModeraBackendOnSteroidsExtension;
use Modera\BackendOnSteroidsBundle\Generators\ShellScriptsGenerator;
use Sensio\Bundle\GeneratorBundle\Command\GeneratorCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

/**
 * @author Sergei Lissovski <sergei.lissovski@gmail.com>
 */
class GenerateScriptsCommand extends GeneratorCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('modera:backend-on-steroids:generate-scripts')
            ->setDescription('Generates shell scripts that can be used to compile all bundles\' javascript files together')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $semanticConfig = $this->getContainer()->getParameter(ModeraBackendOnSteroidsExtension::CONFIG_KEY);
        $compilerConfig = $semanticConfig['compiler'];

        $compilerConfig = array_merge($compilerConfig, array(
            'output_file_dir' => substr($compilerConfig['output_file'], 0, strrpos($compilerConfig['output_file'], '/')),
        ));

        /* @var ShellScriptsGenerator $generator */
        $generator = $this->getGenerator();

        $generatedScripts = $generator->generate($compilerConfig);

        $output->writeln('Following scripts have been generated:');
        foreach ($generatedScripts as $filename) {
            $output->writeln(' -'.$filename);
        }

        $filenames = [];
        foreach ($generatedScripts as $path) {
            $filenames[] = substr($path, strrpos($path, '/') + 1);
        }

        $output->writeln('<info>Please use this following command to make scripts executable (execute from host machine): </info>');
        $output->writeln(
            sprintf('sudo chown `whoami` %s && chmod +x %s', implode(' ', $filenames), implode(' ', $filenames))
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getSkeletonDirs(BundleInterface $bundle = null)
    {
        return [
            __DIR__.'/../Resources/skeleton',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function createGenerator()
    {
        return new ShellScriptsGenerator();
    }
}
