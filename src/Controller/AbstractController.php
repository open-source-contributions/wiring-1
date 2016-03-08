<?php

namespace Wiring\Controller;

use Interop\Container\ContainerInterface;

abstract class AbstractController implements ControllerInterface
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
     * Get an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     * @return mixed Entry.
     */
    public function get($id)
    {
        return $this->container->get($id);
    }

    /**
     * Check if the container can return an entry for the given identifier.
     *
     * @param string $id Identifier of the entry to look for.
     * @return boolean
     */
    public function has($id)
    {
        return $this->container->has($id);
    }
}
