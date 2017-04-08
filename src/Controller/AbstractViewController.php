<?php

namespace Wiring\Controller;

use Wiring\Interfaces\ViewRendererInterface;

abstract class AbstractViewController extends AbstractController
{
    /**
     * @var \Wiring\Interfaces\ViewRendererInterface
     */
    protected $view;

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
