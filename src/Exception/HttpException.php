<?php

namespace Wiring\Exception;

use Zend\Diactoros\Response\JsonResponse;

class HttpException extends \Exception implements HttpExceptionInterface
{
    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * HttpException constructor.
     *
     * @param null $message
     * @param int $statusCode
     * @param \Exception|null $previous
     * @param array $headers
     * @param int $code
     */
    public function __construct($message = null, $statusCode,
                                \Exception $previous = null, array $headers = [], $code = 0)
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;

        parent::__construct($message, $code, $previous);
    }

    /**
     * Return the status code of the http exceptions.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Return an array of headers provided when the exception was thrown.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Returns a response built from the thrown exception.
     *
     * @return \Zend\Diactoros\Response\JsonResponse
     */
    public function getJsonResponse()
    {
        $data = [
            'message' => $this->getMessage(),
            'code' => $this->getStatusCode()
        ];

        return new JsonResponse($data, $this->getStatusCode(), $this->getHeaders());
    }
}
