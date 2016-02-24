<?php

namespace Wiring\Provider;

use Psr\Http\Message\ResponseInterface;

interface ViewRendererInterface
{
    /**
     * Define template engine.
     *
     * @param $engine
     */
    public function __construct($engine);

    /**
     * Get template engine.
     *
     * @return mixed
     */
    public function engine();

    /**
     * Render a new template view.
     *
     * @param string $view Template view name
     * @param array $params View params
     * @return self
     */
    public function render($view, array $params = []);

    /**
     * Write data to the stream.
     *
     * @param string $data The string that is to be written.
     * @return self
     */
    public function write($data);

    /**
     * Return response with JSON header and status.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param int $status
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function to(ResponseInterface $response, $status = 200);
}
