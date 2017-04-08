<?php

namespace Wiring\Controller;

use Wiring\Interfaces\JsonRendererInterface;
use Wiring\Interfaces\ViewRendererInterface;

abstract class AbstractJsonViewController extends AbstractController
{
    /**
     * @var \Wiring\Interfaces\JsonRendererInterface
     */
    protected $json;

    /**
     * @var \Wiring\Interfaces\ViewRendererInterface
     */
    protected $view;

    /**
     * Get JSON renderer.
     *
     * @return \Wiring\Interfaces\JsonRendererInterface
     */
    public function json()
    {
        return $this->get(JsonRendererInterface::class);
    }

    /**
     * Get View renderer.
     *
     * @return \Wiring\Interfaces\ViewRendererInterface
     */
    public function view()
    {
        return $this->get(ViewRendererInterface::class);
    }
}
