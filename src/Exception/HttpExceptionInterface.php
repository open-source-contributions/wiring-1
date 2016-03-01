<?php

namespace Wiring\Exception;

interface HttpExceptionInterface
{
    /**
     * Return the status code of the http exceptions.
     *
     * @return integer
     */
    public function getStatusCode();

    /**
     * Return an array of headers provided when the exception was thrown.
     *
     * @return array
     */
    public function getHeaders();

    /**
     * Returns a response built from the thrown exception.
     *
     * @return \Zend\Diactoros\Response\JsonResponse
     */
    public function getJsonResponse();
}
