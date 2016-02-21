<?php

namespace Wiring\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\EmitterInterface;

class EmitterMiddleware
{
    /**
     * Zend\Diactoros\Response\EmitterInterface;
     */
    protected $emitter;

    public function __construct(EmitterInterface $emitter)
    {
        $this->emitter = $emitter;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param callable $next
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        // Checks if or where headers not have been sent
        if (headers_sent() === false) {
            // Execute code before calling the next middleware
            $this->emitter->emit($response);
        }

        $next($request, $response);
    }
}
