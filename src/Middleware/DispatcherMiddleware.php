<?php

namespace Wiring\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DispatcherMiddleware implements MiddlewareInterface
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var string
     */
    protected $attribute;

    /**
     * Dispatcher constructor.
     *
     * @param \Psr\Container\ContainerInterface
     * @param string $attribute
     *
     * @throws \Exception
     */
    public function __construct(ContainerInterface $container = null, $attribute = "__callable")
    {
        if ($container === null) {
            throw new \Exception("Container not found!");
        }

        $this->container = $container;
        $this->attribute = $attribute;
    }

    /**
     * Called when the object is called as a function.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param callable|null $next
     *
     * @throws \Exception
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $callable = $request->getAttribute($this->attribute);

        if ($callable === null) {
            throw new \Exception("Dispatch callable not found!");
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

        return $next($request, $response);
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
