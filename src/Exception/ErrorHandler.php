<?php

namespace Wiring\Exception;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

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
     * Create error handler.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Exception|\Throwable $exception
     * @param bool $debug
     */
    public function __construct(ServerRequestInterface $request, ResponseInterface $response,
                                LoggerInterface $logger, $exception, $debug = false)
    {
        parent::__construct($request, $response);

        $this->logger = $logger;
        $this->exception = $exception;
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
     * Render an exception into an HTTP or JSON response.
     *
     * @return array
     */
    public function dispatcher()
    {
        $statusCode = method_exists($this->exception, 'getStatusCode') ? $this->exception->getStatusCode() : 400;
        $this->response->withStatus($statusCode);

        $title = $this->debug ?
            'The application could not run because of the following error:' :
            'A website error has occurred. Sorry for the temporary inconvenience.';

        $header = $this->request->getHeaders();
        $message = $this->exception->getMessage();
        $file = $this->exception->getFile();
        $line = $this->exception->getLine();
        $code = $this->exception->getCode();

        $json = isset($header['HTTP_CONTENT_TYPE'][0]) && $header['HTTP_CONTENT_TYPE'][0] == 'application/json';

        // Check content-type is application/json
        if ($json) {
            // Define content-type to json
            $this->response->withHeader('Content-Type', 'application/json');

            $error = [
                'status' => 'error',
                'error' => $title,
                'statusCode' => $statusCode
            ];

            // Check debug
            if ($this->debug) {
                $error['details'] = [
                    'message' => $message,
                    'file' => $file,
                    'line' => $line,
                    'code' => $code
                ];
            }
        } else {
            // Define content-type to html
            $this->response->withHeader('Content-Type', 'text/html');
            $message = sprintf('<span>%s</span>', htmlentities($message));

            $error = [
                'type' => get_class($this->exception),
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
        }

        // Send error to log
        $this->logger->addError($this->exception->getMessage());

        return $error;
    }
}