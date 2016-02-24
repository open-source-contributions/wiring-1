<?php

namespace Wiring\Controller;

use Wiring\Provider\JsonRendererInterface;
use Wiring\Provider\ViewRendererInterface;

abstract class AbstractJsonViewController extends AbstractController
{
    /**
     * @var \Wiring\Provider\JsonRendererInterface
     */
    protected  $json;

    /**
     * @var \Wiring\Provider\ViewRendererInterface
     */
    protected $view;

    /**
     * Get JSON renderer.
     *
     * @return \Wiring\Provider\JsonRendererInterface
     */
    public function json()
    {
        return $this->get(JsonRendererInterface::class);
    }

    /**
     * Get View renderer.
     *
     * @return \Wiring\Provider\ViewRendererInterface
     */
    public function view()
    {
        return $this->get(ViewRendererInterface::class);
    }
}