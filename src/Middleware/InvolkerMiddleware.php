<?php

namespace Wiring\Middleware;

use Exception;
use Throwable;
use Wiring\Factory\ApplicationInterface;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Wiring\Handler\ErrorHandler;
use Wiring\Handler\ErrorHandlerInterface;

class InvolkerMiddleware
{
    /**
     * @var \Interop\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @var array
     */
    protected $middlewares = [];

    /**
     * @var int
     */
    protected $afterMiddleware = -1;

    /**
     * @var int
     */
    protected $beforeMiddleware = -1;

    /**
     * @var array
     */
    protected $middlewareController = [];

    /**
     * @ bool
     */
    protected $isAfterMiddleware = false;

    /**
     * @var bool
     */
    protected $finished = false;

    /**
     * Constructor application.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Interop\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container, ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->container = $container;
        $this->request = $request;
        $this->response = $response;

        // Check container set exist
        if (method_exists($this->container, 'set')) {
            // Inject self application for middlewares freedom
            $this->container->set(ApplicationInterface::class, $this);
        }
    }

    /**
     * Invoke middlewares application.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request = null, ResponseInterface $response = null)
    {
        try {

            if ($request !== null) {
                $this->request = $request;
            }

            if ($response !== null) {
                $this->response = $response;
            }

            // Retrieving key of the middleware
            if ($this->isAfterMiddleware() === true) {
                $key = &$this->afterMiddleware;
            } else {
                $key = &$this->beforeMiddleware;
            }

            $key = $this->getNextMiddleware($key + 1, $this->isAfterMiddleware());

            if ($key === null) {
                return;
            }

            $this->callNextMiddleware($this->middlewares[$key]["callable"]);

        } catch (Exception $e) {
            $this->errorHandler($e, $this->request, $this->response);
        } catch (Throwable $e) {
            $this->errorHandler($e, $this->request, $this->response);
        }
    }

    /**
     * Call the after middlewares.
     */
    public function __destruct()
    {
        $this->setIsAfterMiddleware(true);

        if ($this->isFinished() === false) {
            $this->__invoke();
        }
    }

    /**
     * Add router middleware.
     *
     * @param \Wiring\Middleware\MiddlewareInterface $router
     * @return self
     */
    public function addRouterMiddleware(MiddlewareInterface $router)
    {
        $this->addMiddleware('router', $router);

        return $this;
    }

    /**
     * Add dispatcher middleware.
     *
     * @param \Wiring\Middleware\MiddlewareInterface $dispatcher
     * @return self
     */
    public function addDispatcherMiddleware(MiddlewareInterface $dispatcher)
    {
        $this->addMiddleware('dispatcher', $dispatcher);

        return $this;
    }

    /**
     * Add emitter middleware.
     *
     * @param \Wiring\Middleware\MiddlewareInterface $emitter
     * @return self
     */
    public function addEmitterMiddleware(MiddlewareInterface $emitter)
    {
        $this->addAfterMiddleware('emitter', $emitter);

        return $this;
    }

    /**
     * Get middleware.
     *
     * @param string $key
     * @return null|callable
     */
    public function getMiddleware($key)
    {
        $key = $this->findMiddleware($key);

        if ($key === null) {
            return null;
        }

        return $this->middlewares[$key]["callable"];
    }

    /**
     * Added middleware.
     *
     * @param string $key
     * @param callable $middleware
     * @return self
     */
    public function addMiddleware($key, callable $middleware)
    {
        $this->middlewares[] = ["key" => $key, "callable" => $middleware, "after" => false];

        return $this;
    }

    /**
     * Added after middleware.
     *
     * @param string $key
     * @param callable $middleware
     * @return self
     */
    public function addAfterMiddleware($key, callable $middleware)
    {
        $this->middlewares[] = ["key" => $key, "callable" => $middleware, "after" => true];

        return $this;
    }

    /**
     * Remove middleware.
     *
     * @param string $key
     * @return self
     */
    public function removeMiddleware($key)
    {
        $key = $this->findMiddleware($key);

        if ($key !== null) {
            unset($this->middlewares[$key]);
        }

        return $this;
    }

    /**
     * Find middleware by key.
     *
     * @param string $key
     * @return null|string
     */
    protected function findMiddleware($key)
    {
        foreach ($this->middlewares as $k => $middleware) {
            if ($middleware["key"] === $key) {
                return $k;
            }
        }

        return null;
    }

    /**
     * Get the next middleware.
     *
     * @param string $key
     * @param bool $isAfter
     * @return null|string
     */
    protected function getNextMiddleware($key, $isAfter)
    {
        while (isset($this->middlewares[$key])) {
            // Check is after middleware
            if ($this->middlewares[$key]["after"] === $isAfter) {
                return $key;
            }

            $key++;
        }

        return null;
    }

    /**
     * Call next middleware.
     *
     * @param callable $middleware
     */
    protected function callNextMiddleware(callable $middleware)
    {
        $response = $middleware($this->request, $this->response, $this);

        if ($response) {
            // Check is after middleware
            if ($this->isAfterMiddleware()) {
                $this->finished = true;
            }

            $this->response = $response;
        }
    }

    /**
     * @return mixed
     */
    public function isAfterMiddleware()
    {
        return $this->isAfterMiddleware;
    }

    /**
     * @param mixed $isAfterMiddleware
     * @return InvolkerMiddleware
     */
    public function setIsAfterMiddleware($isAfterMiddleware)
    {
        $this->isAfterMiddleware = $isAfterMiddleware;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isFinished()
    {
        return $this->finished;
    }

    /**
     * @param boolean $finished
     */
    public function setFinished($finished)
    {
        $this->finished = $finished;
    }

    /**
     * Return __invoke method.
     *
     * @return self
     */
    protected function invoker()
    {
        return $this();
    }

    /**
     * Error handler.
     *
     * @param \Exception|\Throwable $error
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return mixed
     * @throws \Wiring\Handler\ErrorHandler
     */
    protected function errorHandler($error, ServerRequestInterface $request, ResponseInterface $response)
    {
        // Check has handler
        if (!$this->container->has(ErrorHandlerInterface::class)) {
            throw new ErrorHandler($request, $response, $error);
        }

        /** @var callable $errorHandler */
        $errorHandler = $this->container->get(ErrorHandlerInterface::class);
        return $errorHandler($request, $response, $error);
    }
}