<?php

namespace Wiring\Interfaces;

interface HttpExceptionInterface
{
    /**
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function getRequest();

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getResponse();
}
