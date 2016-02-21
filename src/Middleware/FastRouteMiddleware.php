<?php

namespace Wiring\Middleware;

use FastRoute\Dispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Wiring\Exception\MethodNotAllowedException;
use Wiring\Exception\MethodNotFoundException;

class FastRouteMiddleware
{
    /**
     * @var \FastRoute\Dispatcher
     */
    protected $fastRoute;

    /**
     * @var string
     */
    protected $attribute;

    /**
     * @param \FastRoute\Dispatcher $fastRoute
     * @param string $attribute
     */
    public function __construct(Dispatcher $fastRoute = null, $attribute = "__callable")
    {
        $this->fastRoute = $fastRoute;
        $this->attribute = $attribute;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param callable $next
     * @throws \Wiring\Exception\MethodNotAllowedException
     * @throws \Wiring\Exception\MethodNotFoundException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $routeInfo = $this->fastRoute->dispatch($request->getMethod(), $request->getUri()->getPath());

        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                // Get request params
                foreach ($routeInfo[2] as $param => $value) {
                    $request = $request->withAttribute($param, $value);
                }
                // Get request with attribute
                $request = $request->withAttribute($this->attribute, $routeInfo[1]);
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedException();
            case Dispatcher::NOT_FOUND:
                throw new MethodNotFoundException();
            default:
                throw new MethodNotFoundException();
        }

        $next($request, $response);
    }

    /**
     * @return \FastRoute\Dispatcher
     */
    public function getFastRoute()
    {
        return $this->fastRoute;
    }

    /**
     * @param \FastRoute\Dispatcher $fastRoute
     */
    public function setFastRoute($fastRoute)
    {
        $this->fastRoute = $fastRoute;
    }

    /**
     * @return string
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * @param string $attribute
     */
    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;
    }
}
