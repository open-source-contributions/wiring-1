<?php

namespace Wiring\Exception;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class MethodNotAllowedException extends HttpException
{
    /**
     * HTTP methods allowed.
     *
     * @var string[]
     */
    protected $allowedMethods;

    /**
     * Create MethodNotAllowed exception.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param string[] $allowedMethods
     */
    public function __construct(ServerRequestInterface $request, ResponseInterface $response, array $allowedMethods)
    {
        $response->withStatus(405);
        parent::__construct($request, $response);

        $this->allowedMethods = $allowedMethods;
    }

    /**
     * Get allowed methods.
     *
     * @return string[]
     */
    public function getAllowedMethods()
    {
        return $this->allowedMethods;
    }
}
