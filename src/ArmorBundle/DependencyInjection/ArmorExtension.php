<?php

namespace ArmorBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class ArmorExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config/services')
        );

        $globalLoader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $globalLoader->load('services.xml');

        $loader->load('controller.xml');
        $loader->load('domain.xml');
        $loader->load('repository.xml');
        $loader->load('communicator.xml');
        $loader->load('provider.xml');
    }
}