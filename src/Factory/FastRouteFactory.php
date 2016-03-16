<?php

namespace Wiring\Factory;

use FastRoute;
use FastRoute\RouteCollector;
use ReflectionClass;
use Wiring\Controller\RestfulControllerInterface;

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
        $dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $route) {
            // Added all routes
            foreach ($this->routes as $param) {
                // Check method is defined
                if (count($param[2]) == 2) {
                    $route->addRoute($param[0], $param[1], $param[2]);
                } elseif (isset($param[2][0])) {
                    // Create object reflection
                    $obj = new ReflectionClass($param[2][0]);
                    // Check object implements restful interface
                    if ($obj->implementsInterface(RestfulControllerInterface::class)) {
                        // Get restful methods
                        $this->addRestfulMethods($route, $param);
                    }
                }
            }
        });

        return $dispatcher;
    }

    /**
     * Added restful routes.
     *
     * @param RouteCollector $route
     * @param array $param
     */
    private function addRestfulMethods($route, $param)
    {
        $uri = $param[1];
        $class = $param[2][0];

        $methods = [
            'index' => ['GET', $uri],
            'read' => ['GET', $uri . '/{id}'],
            'create' => ['POST', $uri],
            'update' => ['PUT', $uri . '/{id}'],
            'delete' => ['DELETE', $uri . '/{id}'],
        ];

        foreach ($methods as $key => $method) {
            $route->addRoute($method[0], $method[1], [$class, $key]);
        }
    }
}
