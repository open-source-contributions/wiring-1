<?php

namespace Wiring\Exception;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class ErrorHandler
{
    /**
     * Invoke error handler.
     *
     * @param  ServerRequestInterface $request  The most recent Request object
     * @param  ResponseInterface      $response The most recent Response object
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $contentType = $request->getHeader('Content-Type');

        if ($contentType === null) {
            $contentType = 'text/html';
        }

        return $response
            ->withHeader('Content-Type', $contentType)
            ->getBody()->write($response->getReasonPhrase());
    }
}