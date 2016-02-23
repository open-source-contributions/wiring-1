<?php

namespace Wiring\Provider;

class ViewRenderer implements ViewRendererInterface
{
    protected $engine;

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
     */
    public function render($view, array $params = [])
    {
        return $this->engine()->render($view, $params);
    }
}
