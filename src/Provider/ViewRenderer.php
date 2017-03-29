<?php

namespace Wiring\Provider;

use Psr\Http\Message\ResponseInterface;

class ViewRenderer implements ViewRendererInterface
{
    /**
     * @var mixed
     */
    protected $engine;

    /**
     * @var string
     */
    protected $data;

    /**
     * @var string
     */
    protected $view;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var bool
     */
    protected $isRender = false;

    /**
     * Define template engine.
     *
     * @param $engine
     */
    public function __construct($engine)
    {
        $this->engine = $engine;
    }

    /**
     * Get template engine.
     *
     * @return mixed
     */
    public function engine()
    {
        return $this->engine;
    }

    /**
     * Render a new template view.
     *
     * @param string $view Template view name
     * @param array $params View params
     *
     * @return self
     */
    public function render($view, array $params = [])
    {
        $this->view = $view;
        $this->params = $params;
        $this->isRender = true;

        return $this;
    }

    /**
     * Write data to the stream.
     *
     * @param string $data The string that is to be written.
     *
     * @return self
     */
    public function write($data)
    {
        $this->data = $data;
        $this->isRender = false;

        return $this;
    }

    /**
     * Return response with JSON header and status.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param int $status
     *
     * @return mixed
     */
    public function to(ResponseInterface $response, $status = 200)
    {
        if ($this->view) {
            $response->getBody()->write($this->engine()->render($this->view, $this->params));
        } else if ($this->data) {
            $response->getBody()->write($this->data);
        }

        return $response->withStatus($status);
    }
}
