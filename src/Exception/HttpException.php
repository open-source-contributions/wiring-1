<?php

namespace Wiring\Exception;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Wiring\Interfaces\HttpExceptionInterface;

class HttpException extends \Exception implements HttpExceptionInterface
{
    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * Http exception constructor.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     */
    public function __construct(ServerRequestInterface $request, ResponseInterface $response)
    {
        parent::__construct();

        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }
}
