<?php

namespace Wiring\Middleware;

use FastRoute\Dispatcher;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Wiring\Exception\MethodNotAllowedException;
use Wiring\Exception\MethodNotAllowedHandlerInterface;
use Wiring\Exception\NotFoundException;
use Wiring\Exception\NotFoundHandlerInterface;

class FastRouteMiddleware implements MiddlewareInterface
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var \FastRoute\Dispatcher
     */
    protected $fastRoute;

    /**
     * @var string
     */
    protected $attribute;

    /**
     * @param \Psr\Container\ContainerInterface $container
     * @param \FastRoute\Dispatcher $fastRoute
     * @param string $attribute
     */
    public function __construct(ContainerInterface $container, Dispatcher $fastRoute = null, $attribute = "__callable")
    {
        $this->container = $container;
        $this->fastRoute = $fastRoute;
        $this->attribute = $attribute;
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
        $routeInfo = $this->fastRoute->dispatch($request->getMethod(), $request->getUri()->getPath());

        if ($routeInfo[0] == Dispatcher::FOUND) {
            // Get request params
            foreach ($routeInfo[2] as $param => $value) {
                $request = $request->withAttribute($param, $value);
            }
            // Get request with attribute
            $request = $request->withAttribute($this->attribute, $routeInfo[1]);
            return $next($request, $response);
        }

        if ($routeInfo[0] == Dispatcher::METHOD_NOT_ALLOWED) {
            // Check has handler
            if (!$this->container->has(MethodNotAllowedHandlerInterface::class)) {
                throw new MethodNotAllowedException($request, $response, $routeInfo[1]);
            }
            /** @var callable $notAllowedHandler */
            $notAllowedHandler = $this->container->get(MethodNotAllowedHandlerInterface::class);
            return $notAllowedHandler($request, $response, $routeInfo[1]);
        }

        // Check has handler
        if (!$this->container->has(NotFoundHandlerInterface::class)) {
            throw new NotFoundException($request, $response);
        }

        /** @var callable $notFoundHandler */
        $notFoundHandler = $this->container->get(NotFoundHandlerInterface::class);
        return $notFoundHandler($request, $response);
    }

    /**
     * Get dependency injection container.
     *
     * @return \Psr\Container\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Set dependency injection container.
     *
     * @param \Psr\Container\ContainerInterface $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
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
     * Get action attribute name.
     *
     * @return string
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * Set action attribute name.
     *
     * @param string $attribute
     */
    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;
    }
}
