<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        return array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),

            new Sli\ExpanderBundle\SliExpanderBundle(),
            new Modera\FoundationBundle\ModeraFoundationBundle(),
            new Modera\BackendOnSteroidsBundle\ModeraBackendOnSteroidsBundle(),

            new Modera\BackendOnSteroidsBundle\Tests\Fixtures\Bundles\BackendDummyBundle\ModeraBackendDummyBundle(),
        );
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config.yml');
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        return sys_get_temp_dir().'/ModeraBackendOnSteroidsBundle/cache';
    }

    /**
     * @return string
     */
    public function getLogDir()
    {
        return sys_get_temp_dir().'/ModeraBackendOnSteroidsBundle/logs';
    }
}
