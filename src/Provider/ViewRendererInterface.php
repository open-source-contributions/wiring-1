<?php

namespace Wiring\Provider;

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
     */
    public function render($view, array $params = []);
}
