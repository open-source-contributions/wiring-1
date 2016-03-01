<?php

namespace Wiring\Controller;

use Wiring\Provider\ViewRendererInterface;

abstract class AbstractViewController extends AbstractController
{
    /**
     * @var \Wiring\Provider\ViewRendererInterface
     */
    protected $view;

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
