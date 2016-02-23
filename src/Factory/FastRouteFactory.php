<?php

namespace Wiring\Factory;

use FastRoute;
use FastRoute\RouteCollector;

class FastRouteFactory
{
    protected $routes;

    /**
     * Set routes constructor.
     *
     * @param array $routes
     */
    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    /**
     * Get routes from dispatcher.
     *
     * @return mixed
     */
    public function getDispatcher()
    {
        $dispatcher = FastRoute\simpleDispatcher(function(RouteCollector $route) {
            // Added all routes
            foreach ($this->routes as $param) {
                $route->addRoute($param[0], $param[1], $param[2]);
            }
        });

        return $dispatcher;
    }
}
