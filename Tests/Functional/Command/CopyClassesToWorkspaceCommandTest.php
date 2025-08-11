<?php

namespace Modera\BackendOnSteroidsBundle\Tests\Functional\Command;

use Modera\BackendOnSteroidsBundle\Tests\Fixtures\TestOutput;
use Modera\FoundationBundle\Testing\FunctionalTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Filesystem\Filesystem;

class CopyClassesToWorkspaceCommandTest extends FunctionalTestCase
{
    public static function cleanUp(): void
    {
        /* @var Filesystem $filesystem */
        $filesystem = self::getContainer()->get('modera_backend_on_steroids.filesystem');

        $dir = getcwd().DIRECTORY_SEPARATOR.'.mega-steroids';

        if ($filesystem->exists($dir)) {
            $filesystem->remove($dir);
        }
    }

    public static function doTearDownAfterClass(): void
    {
        static::cleanUp();
    }

    public static function doSetUpBeforeClass(): void
    {
        static::cleanUp();
    }

    public function testExecute(): void
    {
        $app = new Application(self::getContainer()->get('kernel'));
        $app->setAutoExit(false);

        $input = new ArrayInput(array(
            'command' => 'modera:backend-on-steroids:copy-classes-to-workspace',
        ));
        $input->setInteractive(false);

        $output = new TestOutput();

        $result = $app->run($input, $output);

        $this->assertEquals(0, $result);

        $files = [
            ['.mega-steroids'],
            ['.mega-steroids', 'packages', 'bundles', 'src', 'Modera', 'backenddummy', 'runtime', 'MegaPlugin.js'],
            ['.mega-steroids', 'packages', 'bundles', 'src', 'Modera', 'backenddummy', 'runtime', 'panel', 'MegaPanel.js'],
        ];

        foreach ($files as $file) {
            $filepath = implode(DIRECTORY_SEPARATOR, array_merge([getcwd()], $file));

            $filesize = filesize($filepath);

            $this->assertTrue(file_exists($filepath), sprintf('File %s is not found.', $filepath));
            $this->assertTrue(false !== $filesize && 0 !== $filesize);
        }
    }
}
