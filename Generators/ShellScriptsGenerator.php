<?php

namespace Modera\BackendOnSteroidsBundle\Generators;

use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Sergei Lissovski <sergei.lissovski@gmail.com>
 */
class ShellScriptsGenerator extends Generator
{
    public function generate(array $compilerConfig)
    {
        $cwd = getcwd();

        $scripts = [
            'setup', 'cleanup', 'compile-bundles', 'compile-mjr'
        ];
        $generatedScripts = [];

        foreach ($scripts as $token) {
            $filename = $cwd . DIRECTORY_SEPARATOR  . 'steroids-' . $token . '.sh';

            $this->renderFile("steroids-$token.sh.twig", $filename, $compilerConfig);

            if (file_exists($filename)) {
                $generatedScripts[] = $filename;
            }
        }

        return $generatedScripts;
    }
}