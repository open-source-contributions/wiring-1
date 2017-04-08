<?php

namespace Wiring\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Wiring\Interfaces\MiddlewareInterface;
use Zend\Diactoros\Response\EmitterInterface;

class EmitterMiddleware implements MiddlewareInterface
{
    /**
     * @var \Zend\Diactoros\Response\EmitterInterface
     */
    protected $emitter;

    /**
     * @param \Zend\Diactoros\Response\EmitterInterface $emitter
     */
    public function __construct(EmitterInterface $emitter)
    {
        $this->emitter = $emitter;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param callable|null $next
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        // Checks if or where headers not have been sent
        if (headers_sent() === false) {
            // Execute code before calling the next middleware
            $this->emitter->emit($response);
        }

        return $next($request, $response);
    }
}
