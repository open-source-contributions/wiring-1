<?php

namespace Wiring\Controller;

use Wiring\Provider\JsonRendererInterface;

abstract class AbstractJsonController extends AbstractController
{
    /**
     * @var \Wiring\Provider\JsonRendererInterface
     */
    protected  $json;

    /**
     * Get JSON renderer.
     *
     * @return \Wiring\Provider\JsonRendererInterface
     */
    public function json()
    {
        return $this->get(JsonRendererInterface::class);
    }
}