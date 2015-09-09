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
            'steroids-setup', 'steroids-cleanup', 'steroids-compile'
        ];
        $generatedScripts = [];

        foreach ($scripts as $token) {
            $filename = $cwd . DIRECTORY_SEPARATOR . $token . '.sh';

            $this->renderFile("$token.sh.twig", $filename, $compilerConfig);

            if (file_exists($filename)) {
                $generatedScripts[] = $filename;
            }
        }

        return $generatedScripts;
    }
}