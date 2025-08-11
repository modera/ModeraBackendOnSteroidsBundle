<?php

namespace Modera\BackendOnSteroidsBundle\Tests\Functional\DependencyInjection;

use Modera\BackendOnSteroidsBundle\DependencyInjection\ModeraBackendOnSteroidsExtension;
use Modera\FoundationBundle\Testing\FunctionalTestCase;

class ModeraBackendOnSteroidsExtensionTest extends FunctionalTestCase
{
    public function testHowWellContainerConfigured(): void
    {
        $provider = self::getContainer()->get('modera_backend_on_steroids.extjs_classes_paths_provider');

        $this->assertInstanceOf('Modera\ExpanderBundle\Ext\ContributorInterface', $provider);

        $config = self::getContainer()->getParameter(ModeraBackendOnSteroidsExtension::CONFIG_KEY);

        $this->assertTrue(is_array($config));
    }
}
