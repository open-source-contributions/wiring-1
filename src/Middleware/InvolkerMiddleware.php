<?php

namespace Wiring\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class InvolkerMiddleware
{
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
     */
    public function __construct(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->request = $request;
        $this->response = $response;
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
}