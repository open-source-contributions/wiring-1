<?php

namespace Wiring\Provider;

use Psr\Http\Message\ResponseInterface;
use InvalidArgumentException;

class JsonRenderer implements JsonRendererInterface
{
    /**
     * Write data with JSON encode.
     * 
     * @param array $data The data
     * @param int $encodingOptions JSON encoding options
     * @return self
     */
    public function render($data, $encodingOptions = 0)
    {
        return $this->jsonEncode($data, $encodingOptions);
    }

    /**
     * Return response with JSON header and status.
     * 
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param int $status
     * @return mixed
     */
    public function response(ResponseInterface $response, $status = 200)
    {
        return $response->withStatus($status)->withHeader('Content-Type', 'application/json;charset=utf-8');
    }

    /**
     * Encode the provided data to JSON.
     * 
     * @param array $data The data
     * @param int $encodingOptions JSON encoding options
     * @return string JSON
     * @throws InvalidArgumentException if unable to encode the $data to JSON
     */
    private function jsonEncode($data, $encodingOptions)
    {
        if (is_resource($data)) {
            throw new InvalidArgumentException('Cannot JSON encode resources');
        }

        // Clear json_last_error()
        json_encode(null);

        $json = json_encode($data, $encodingOptions);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidArgumentException(sprintf('Unable to encode data to JSON in %s: %s', 
                __CLASS__, json_last_error_msg()));
        }

        return $json;
    }
}