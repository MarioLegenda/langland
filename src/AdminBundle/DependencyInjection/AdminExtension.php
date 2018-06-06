<?php

namespace AdminBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class AdminExtension extends Extension
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

        $loader->load('command.xml');
        $loader->load('form.xml');
        $loader->load('listener.xml');
        $loader->load('presentation.xml');
        $loader->load('controller.xml');
        $loader->load('implementation.xml');
        $loader->load('repository.xml');
        $loader->load('resolver.xml');
        $loader->load('infrastructure.xml');
        $loader->load('communication.xml');
    }
}