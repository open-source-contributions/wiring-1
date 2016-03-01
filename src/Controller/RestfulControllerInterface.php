<?php

namespace Wiring\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

interface RestfulControllerInterface
{
    /**
     * List an existing resource.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function index(ServerRequestInterface $request, ResponseInterface $response);

    /**
     * Get an existing resource.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function read(ServerRequestInterface $request, ResponseInterface $response);
    
    /**
     * Create an existing resource.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function create(ServerRequestInterface $request, ResponseInterface $response);

    /**
     * Update an existing resource.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function update(ServerRequestInterface $request, ResponseInterface $response);

    /**
     * Delete an existing resource.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function delete(ServerRequestInterface $request, ResponseInterface $response);
}
