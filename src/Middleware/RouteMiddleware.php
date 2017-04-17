<?php

namespace Wiring\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Wiring\Interfaces\MiddlewareInterface;

class RouteMiddleware implements MiddlewareInterface
{
    /**
     * @var mixed
     */
    protected $route;

    /**
     * @param mixed $route
     */
    public function __construct($route = null)
    {
        $this->route = $route;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param callable|null $next
     *
     * @throws \Wiring\Exception\MethodNotAllowedException
     * @throws \Wiring\Exception\NotFoundException
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        return $this->route->dispatch($request, $response);
    }

    /**
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param mixed $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }
}
