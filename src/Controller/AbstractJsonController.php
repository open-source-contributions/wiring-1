<?php

namespace Wiring\Controller;

use Wiring\Interfaces\JsonRendererInterface;

abstract class AbstractJsonController extends AbstractController
{
    /**
     * @var \Wiring\Interfaces\JsonRendererInterface
     */
    protected $json;

    /**
     * Get JSON renderer.
     *
     * @return \Wiring\Interfaces\JsonRendererInterface
     */
    public function json()
    {
        return $this->get(JsonRendererInterface::class);
    }
}
