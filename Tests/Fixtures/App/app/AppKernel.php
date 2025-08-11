<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles(): iterable
    {
        return [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),

            new Modera\ExpanderBundle\ModeraExpanderBundle(),
            new Modera\FoundationBundle\ModeraFoundationBundle(),
            new Modera\BackendOnSteroidsBundle\ModeraBackendOnSteroidsBundle(),

            new Modera\BackendOnSteroidsBundle\Tests\Fixtures\Bundles\BackendDummyBundle\ModeraBackendDummyBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__.'/config/config.yml');
    }

    public function getCacheDir(): string
    {
        return sys_get_temp_dir().'/ModeraBackendOnSteroidsBundle/cache';
    }

    public function getLogDir(): string
    {
        return sys_get_temp_dir().'/ModeraBackendOnSteroidsBundle/logs';
    }
}
