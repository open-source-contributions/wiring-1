<?php

namespace Wiring\Exception;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class NotFoundException extends HttpException
{
    /**
     * Create NotFound exception.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function __construct(ServerRequestInterface $request, ResponseInterface $response)
    {
        $response->withStatus(404);
        parent::__construct($request, $response);
    }
}
