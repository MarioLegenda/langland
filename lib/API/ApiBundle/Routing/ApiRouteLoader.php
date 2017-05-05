<?php

namespace API\ApiBundle\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class ApiRouteLoader extends Loader
{
    /**
     * @var string $route
     */
    private $route;
    /**
     * @var bool $loaded
     */
    private $loaded = false;
    /**
     * ApiRouteLoader constructor.
     * @param string $route
     */
    public function __construct(string $route)
    {
        $this->route = $route;
    }
    /**
     * @param mixed $resource
     * @param null $type
     * @return RouteCollection
     */
    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add the "bluedot api" loader twice');
        }

        $routes = new RouteCollection();

        $defaults = array(
            '_controller' => 'ApiBundle:Result:result',
        );

        $route = new Route($this->route, $defaults);
        // add the new route to the route collection
        $routeName = 'api_blue_dot_route';
        $routes->add($routeName, $route);

        $this->loaded = true;

        return $routes;
    }
    /**
     * @param mixed $resource
     * @param null $type
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        return 'blue_dot_base_route' === $type;
    }
}