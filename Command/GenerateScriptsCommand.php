<?php

namespace Modera\BackendOnSteroidsBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Modera\BackendOnSteroidsBundle\DependencyInjection\ModeraBackendOnSteroidsExtension;
use Modera\BackendOnSteroidsBundle\Generators\ShellScriptsGenerator;

/**
 * @author Sergei Lissovski <sergei.lissovski@gmail.com>
 */
class GenerateScriptsCommand extends ContainerAwareCommand
{
    /**
     * @var ShellScriptsGenerator
     */
    private $generator;

    /**
     * @return ShellScriptsGenerator
     */
    protected function getGenerator()
    {
        if (null === $this->generator) {
            $this->generator = new ShellScriptsGenerator();
            $this->generator->setSkeletonDirs([ __DIR__.'/../Resources/skeleton' ]);
        }

        return $this->generator;
    }

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
}
