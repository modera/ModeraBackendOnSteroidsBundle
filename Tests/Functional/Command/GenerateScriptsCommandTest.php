<?php

namespace Modera\BackendOnSteroidsBundle\Tests\Functional\Command;

use Modera\BackendOnSteroidsBundle\Tests\Fixtures\TestOutput;
use Modera\FoundationBundle\Testing\FunctionalTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

class GenerateScriptsCommandTest extends FunctionalTestCase
{
    // see doSetUpBeforeClass()
    private static $scriptsDir;

    public static function getScriptsPaths(): array
    {
        $result = [];

        foreach (['cleanup', 'compile-mjr', 'compile-bundles', 'setup'] as $name) {
            $result[] = self::$scriptsDir.'steroids-'.$name.'.sh';
        }

        return $result;
    }

    private static function deleteGeneratedScripts(): void
    {
        foreach (self::getScriptsPaths() as $filepath) {
            if (file_exists($filepath)) {
                unlink($filepath);
            }
        }
    }

    public static function doSetUpBeforeClass(): void
    {
        self::$scriptsDir = __DIR__.'/../../../';

        static::deleteGeneratedScripts();
    }

    public static function doTearDownAfterClass(): void
    {
        static::deleteGeneratedScripts();
    }

    public function testExecute(): void
    {
        $app = new Application(self::getContainer()->get('kernel'));
        $app->setAutoExit(false);

        $input = new ArrayInput(array(
            'command' => 'modera:backend-on-steroids:generate-scripts',
        ));
        $input->setInteractive(false);

        $output = new TestOutput();

        $result = $app->run($input, $output);

        $this->assertEquals(0, $result);

        foreach (self::getScriptsPaths() as $filepath) {
            $this->assertTrue(file_exists($filepath));

            $contents = @file_get_contents($filepath);

            $this->assertTrue('' != $contents);
        }
    }
}
