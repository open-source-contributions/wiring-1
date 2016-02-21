<?php

namespace Wiring\Middleware;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DispatcherMiddleware
{
    /**
     * @var \Interop\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var string
     */
    protected $attribute;

    /**
     * @param \Interop\Container\ContainerInterface
     * @param string $attribute
     */
    public function __construct(ContainerInterface $container = null, $attribute = "__callable")
    {
        if ($container !== null) {
            $this->container = $container;
        }

        $this->attribute = $attribute;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param callable $next
     * @throws \Exception
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $callable = $request->getAttribute($this->attribute);

        if ($callable === null) {
            throw new \Exception("Dispatchable callable not found!");
        }

        if (is_array($callable) && is_string($callable[0])) {
            // Construct a new object for the requested action
            $object = $this->container->get($callable[0]);

            $reflection = new \ReflectionMethod($object, $callable[1]);
            $reflection->setAccessible(true);

            $response = $reflection->invoke($object, $request, $response);
        } else {
            // Check is not callable
            if (!is_callable($callable)) {
                // Construct a new object for the requested action
                $callable = $this->container->get($callable);
            }

            $reflection = new \ReflectionMethod($callable, $callable[1]);
            $reflection->setAccessible(true);

            $response = $reflection->invoke($callable, $request, $response);
        }

        $next($request, $response);
    }
}
