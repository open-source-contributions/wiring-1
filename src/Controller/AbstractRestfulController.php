<?php

namespace Wiring\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class AbstractRestfulController extends AbstractJsonController implements RestfulControllerInterface
{
    /**
     * List an existing resource.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function index(ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = $this->methodNotAllowed();

        return $this->json()->render($data)->to($response, 405);
    }

    /**
     * Get an existing resource.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function read(ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = $this->methodNotAllowed();

        return $this->json()->render($data)->to($response, 405);
    }

    /**
     * Create an existing resource.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function create(ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = $this->methodNotAllowed();

        return $this->json()->render($data)->to($response, 405);
    }

    /**
     * Update an existing resource.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function update(ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = $this->methodNotAllowed();

        return $this->json()->render($data)->to($response, 405);
    }

    /**
     * Delete an existing resource.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function delete(ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = $this->methodNotAllowed();

        return $this->json()->render($data)->to($response, 405);
    }

    /**
     * Get response data success.
     *
     * @param string $message
     * @param int $status
     * @param array $data
     * @return array
     */
    public function success($message = 'OK', $status = 200, $data = [])
    {
        $data = $this->data('success', $message, $status, $data);

        return $data;
    }

    /**
     * Get response data error.
     *
     * @param string $message
     * @param int $status
     * @param array $data
     * @return array
     */
    public function error($message = 'Bad Request', $status = 400, $data = [])
    {
        $data = $this->data('error', $message, $status, $data);

        return $data;
    }

    /**
     * Get response data fail.
     *
     * @param string $message
     * @param int $status
     * @param array $data
     * @return array
     */
    public function fail($message = 'Internal Server Error', $status = 500, $data = [])
    {
        $data = $this->data('fail', $message, $status, $data);

        return $data;
    }

    /**
     * Get response data.
     *
     * @param string $status
     * @param string $message
     * @param int $code
     * @param array $data
     * @return array
     */
    public function data($status, $message = 'OK', $code = 200, $data = [])
    {
        $data = [
            'status' => $status,
            'message' => $message,
            'code' => $code,
            'data' => $data
        ];

        return $data;
    }

    /**
     * Get Method Not Allowed.
     *
     * @return array
     */
    private function methodNotAllowed()
    {
        return $this->error('Method Not Allowed', 405);
    }
}
