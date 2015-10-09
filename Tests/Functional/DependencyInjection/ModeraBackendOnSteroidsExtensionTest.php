<?php

namespace Modera\BackendOnSteroidsBundle\Tests\Functional\DependencyInjection;

use Modera\BackendOnSteroidsBundle\DependencyInjection\ModeraBackendOnSteroidsExtension;
use Modera\FoundationBundle\Testing\FunctionalTestCase;

/**
 * @author Sergei Lissovski <sergei.lissovski@gmail.com>
 */
class ModeraBackendOnSteroidsExtensionTest extends FunctionalTestCase
{
    public function testHowWellContainerConfigured()
    {
        $provider = self::$container->get('modera_backend_on_steroids.extjs_classes_paths_provider');

        $this->assertInstanceOf('Sli\ExpanderBundle\Ext\ContributorInterface', $provider);

        $config = self::$container->getParameter(ModeraBackendOnSteroidsExtension::CONFIG_KEY);

        $this->assertTrue(is_array($config));
    }
}
