<?php

namespace Wiring\Controller;

use Interop\Container\ContainerInterface;

abstract class AbstractController implements ContainerInterface
{
    /**
     * @var \Interop\Container\ContainerInterface
     */
    protected $container;

    /**
     * Create container.
     *
     * @param \Interop\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Get container.
     *
     * @param string $id
     * @return \Interop\Container\ContainerInterface
     */
    public function get($id)
    {
        return $this->container->get($id);
    }

    /**
     * Check container.
     *
     * @param string $id
     * @return \Interop\Container\ContainerInterface
     */
    public function has($id)
    {
        return $this->container->has($id);
    }
}