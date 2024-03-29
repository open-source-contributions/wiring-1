<?php

namespace Wiring\Interfaces;

use Psr\Http\Message\ResponseInterface;

interface EmitterInterface
{
    /**
     * Emit a response.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function emit(ResponseInterface $response);
}
