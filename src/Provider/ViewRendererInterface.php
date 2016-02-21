<?php

namespace Wiring\Provider;

interface ViewRendererInterface
{
    /**
     * Create a new template engine instance.
     * 
     * @param string $directory View path
     * @param string $fileExtension File extension
     */
    public function __construct($directory, $fileExtension);

    /**
     * Render a new template view.
     * 
     * @param string $view Template view name
     * @param array $params View params
     */
    public function render($view, array $params = []);

    /**
     * Get template engine methods.
     *
     * @return \League\Plates\Engine self
     */
    public function engine();
}