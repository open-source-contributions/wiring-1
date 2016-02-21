<?php

namespace Wiring\Factory;

use FastRoute;
use FastRoute\RouteCollector;

class FastRouteFactory
{
    /**
     * Get routes from dispatcher.
     * 
     * @param array $routes
     * @return FastRoute\Dispatcher
     */
    public static function fromDispatcher(array $routes)
    {
        $dispatcher = FastRoute\simpleDispatcher(function(RouteCollector $route) use ($routes) {
            // Added all routes
            foreach ($routes as $param) {
                $route->addRoute($param[0], $param[1], $param[2]);
            }
        });

        return $dispatcher;
    }
}