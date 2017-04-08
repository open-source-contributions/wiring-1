<?php

namespace Wiring\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Wiring\Exception\HttpException;

class ErrorHandler extends HttpException
{
    /**
     * @var \Exception|\Throwable
     */
    protected $exception;

    /**
     * @var \Psr\Log\LoggerInterface;
     */
    protected $logger;

    /**
     * @var bool
     */
    protected $debug;

    /**
     * @var bool
     */
    protected $isJson = false;

    /**
     * Create error handler.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Exception|\Throwable $exception
     * @param bool $debug
     */
    public function __construct(ServerRequestInterface $request, ResponseInterface $response,
                                $exception, LoggerInterface $logger = null, $debug = false)
    {
        parent::__construct($request, $response);

        $this->exception = $exception;
        $this->logger = $logger;
        $this->debug = $debug;
    }

    /**
     * Get exception.
     *
     * @return \Exception|\Throwable
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * Return an error into an HTTP or JSON data array.
     *
     * @param string $title
     *
     * @return array
     */
    public function error($title = null)
    {
        if ($title == null) {
            $title = $this->debug ?
                'The application could not run because of the following error:' :
                'A website error has occurred. Sorry for the temporary inconvenience.';
        }

        $type = $this->request->getHeader('Content-Type');
        $msg = $this->exception->getMessage();
        $file = $this->exception->getFile();
        $line = $this->exception->getLine();
        $code = $this->exception->getCode();

        $statusCode = method_exists($this->exception, 'getStatusCode') ? $this->exception->getStatusCode() : null;

        // Check status code is null
        if ($statusCode == null) {
            $statusCode = $code >= 100 && $code <= 500 ? $code : 400;
        }

        $this->response->withStatus($statusCode);

        // Check logger exist
        if ($this->logger !== null) {
            // Send error to log
            $this->logger->error($this->exception->getMessage());
        }

        $this->isJson = isset($type[0]) && $type[0] == 'application/json';

        // Check content-type is application/json
        if ($this->isJson) {
            // Define content-type to json
            $this->response->withHeader('Content-Type', 'application/json');

            $error = [
                'status' => 'error',
                'status_code' => $statusCode,
                'error' => $title,
                'details' => []
            ];

            // Check debug
            if ($this->debug) {
                $error['details'] = [
                    'message' => $msg,
                    'file' => $file,
                    'line' => $line,
                    'code' => $code
                ];
            }

            return $error;
        }

        // Define content-type to html
        $this->response->withHeader('Content-Type', 'text/html');
        $message = sprintf('<span>%s</span>', htmlentities($msg));

        $error = [
            'type' => get_class($this->exception),
            $error['status_code'] = $statusCode,
            'message' => $message
        ];

        // Check debug
        if ($this->debug) {
            $trace = $this->exception->getTraceAsString();
            $trace = sprintf('<pre>%s</pre>', htmlentities($trace));

            $error['file'] = $file;
            $error['line'] = $line;
            $error['code'] = $code;
            $error['trace'] = $trace;
        }

        $error['debug'] = $this->debug;
        $error['title'] = $title;

        return $error;
    }

    /**
     * Check is JSON.
     *
     * @return bool
     */
    public function isJson()
    {
        return $this->isJson;
    }
}
