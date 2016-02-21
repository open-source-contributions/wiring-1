<?php

namespace Wiring\Provider;

use Psr\Http\Message\ResponseInterface;

interface JsonRendererInterface
{
    /**
     * Write data with JSON encode.
     * 
     * @param array $data The data
     * @param int $encodingOptions JSON encoding options
     * @return self
     */
    public function render($data, $encodingOptions = 0);

    /**
     * Return response with JSON header and status.
     * 
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param int $status
     * @return mixed
     */
    public function response(ResponseInterface $response, $status = 200);
}