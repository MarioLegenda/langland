<?php

namespace API\ApiBundle\DependencyInjection;

use API\ApiBundle\Exception\InvalidConfiguration;
use API\ApiBundle\Routing\ApiRouteLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;

class ApiExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        if (!$container->hasParameter('blue_dot_dir') or !$container->hasParameter('api_base_route')) {
            throw new InvalidConfiguration(
                sprintf(
                    'Invalid BlueDot api configuration. You have to provide values for blue_dot_dir and api_base_route under blue_dot_api parameter values'
                )
            );
        }

        $routerLoaderDefinition = new Definition(ApiRouteLoader::class, array(
            '%api_base_route%'
        ));

        $routerLoaderDefinition->setTags(array(
            'routing.loader' => array()
        ));

        $container->setDefinition('api.blue_dot.route_loader', $routerLoaderDefinition);
    }
}