<?php

namespace Modera\BackendOnSteroidsBundle\Tests\Unit\DependencyInjection;

use Modera\BackendOnSteroidsBundle\Contributions\JsResourcesProvider;
use Modera\BackendOnSteroidsBundle\DependencyInjection\ModeraBackendOnSteroidsExtension;

/**
 * @author    Sergei Lissovski <sergei.lissovski@modera.org>
 * @copyright 2015 Modera Foundation
 */
class ModeraBackendOnSteroidsExtensionTest extends \PHPUnit_Framework_TestCase
{
    private function processConfigAndReturnInvocationStats($injectScripts)
    {
        $ext = new ModeraBackendOnSteroidsExtension();

        $config = array(
            array(
                'inject_scripts' => $injectScripts
            )
        );

        $containerBuilder = \Phake::mock('Symfony\Component\DependencyInjection\ContainerBuilder');

        $ext->load($config, $containerBuilder);

        $params = array(
            'names' => [],
            'definitions' => []
        );

        \Phake::verify($containerBuilder, \Phake::atLeast(1))->setDefinition(
            \Phake::captureAll($params['names']), \Phake::captureAll($params['definitions'])
        );

        return $params;
    }

    public function testLoadWithoutInjectScripts()
    {
        $stats = $this->processConfigAndReturnInvocationStats(false);

        $this->assertFalse(array_search('modera_backend_on_steroids.contributions.js_resources_provider', $stats['names']));
    }

    public function testLoadWithInjectScripts()
    {
        $stats = $this->processConfigAndReturnInvocationStats(true);

        $index = array_search('modera_backend_on_steroids.contributions.js_resources_provider', $stats['names']);

        $this->assertTrue(false !== $index);

        /* @var \Symfony\Component\DependencyInjection\Definition $def */
        $def = $stats['definitions'][$index];

        $this->assertInstanceOf('Symfony\Component\DependencyInjection\Definition', $def);
        $this->assertEquals(JsResourcesProvider::clazz(), $def->getClass());
        $this->assertEquals(['modera_mjr_integration.js_resources_provider'], array_keys($def->getTags()));
        $args = $def->getArguments();
        $this->assertEquals(1, count($args));
        /* @var \Symfony\Component\DependencyInjection\Reference $firstArg */
        $firstArg = $args[0];
        $this->assertInstanceOf('Symfony\Component\DependencyInjection\Reference', $firstArg);
        $this->assertEquals('service_container', (string)$firstArg);
    }
}