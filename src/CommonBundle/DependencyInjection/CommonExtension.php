<?php

namespace CommonBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class CommonExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config/services')
        );

        $loader->load('command.xml');
        $loader->load('data_provider.xml');
        $loader->load('library.xml');
        $loader->load('listener.xml');
        $loader->load('blue_dot.xml');
    }
}