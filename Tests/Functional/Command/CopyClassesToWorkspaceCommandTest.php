<?php

namespace Modera\BackendOnSteroidsBundle\Tests\Functional\Command;

use Modera\BackendOnSteroidsBundle\Tests\Fixtures\TestOutput;
use Modera\FoundationBundle\Testing\FunctionalTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Sergei Lissovski <sergei.lissovski@gmail.com>
 */
class CopyClassesToWorkspaceCommandTest extends FunctionalTestCase
{
    static public function cleanUp()
    {
        /* @var Filesystem $filesystem */
        $filesystem = self::$container->get('filesystem');

        $dir = getcwd() . DIRECTORY_SEPARATOR . '.mega-steroids';

        if ($filesystem->exists($dir)) {
            $filesystem->remove($dir);
        }
    }

    /**
     * {@inheritDoc}
     */
    static public function doTearDownAfterClass()
    {
        static::cleanUp();
    }

    static public function doSetUpBeforeClass()
    {
        static::cleanUp();
    }

    public function testExecute()
    {
        $app = new Application(self::$container->get('kernel'));
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
            ['.mega-steroids', 'packages', 'bundles', 'src', 'Modera', 'backenddummy', 'runtime', 'panel', 'MegaPanel.js']
        ];

        foreach ($files as $file) {
            $filepath = implode(DIRECTORY_SEPARATOR, array_merge([getcwd()], $file));

            $filesize = filesize($filepath);

            $this->assertTrue(file_exists($filepath), sprintf('File %s is not found.', $filepath));
            $this->assertTrue(false !== $filesize && 0 !== $filesize);
        }
    }
}