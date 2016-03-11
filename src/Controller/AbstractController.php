<?php

namespace Wiring\Controller;

use Exception;
use Interop\Container\ContainerInterface;
use Wiring\Provider\DatabaseInterface;

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

    /**
     * Resolves an entry by its name.
     * If given a class name, it will return a new instance of that class.
     *
     * @param string $name Entry name or a class name.
     * @param array $parameters Optional parameters to use to build the entry. Use this to force specific
     *                           parameters to specific values. Parameters not defined in this array will
     *                           be automatically resolved.
     *
     * @throws \Exception       Error while resolving the entry.
     * @return mixed
     */
    public function make($name, array $parameters = [])
    {
        if (!method_exists($this->container, 'make')) {
            throw new Exception('Container method not found');
        }

        return $this->container->make($name, $parameters);
    }

    /**
     * Call the given function using the given parameters.
     *
     * Missing parameters will be resolved from the container.
     *
     * @param callable $callable Function to call.
     * @param array $parameters Parameters to use. Can be indexed by the parameter names
     *                             or not indexed (same order as the parameters).
     *                             The array can also contain DI definitions, e.g. DI\get().
     *
     * @throws \Exception
     * @return mixed Result of the function.
     */
    public function call($callable, array $parameters = [])
    {
        if (!method_exists($this->container, 'call')) {
            throw new Exception('Container method not found');
        }

        return $this->container->call($callable, $parameters);
    }

    /**
     * Define an object or a value in the container.
     *
     * @param string $name Entry name
     * @param mixed $value Value, use definition helpers to define objects
     * @throws \Exception
     */
    public function set($name, $value)
    {
        if (!method_exists($this->container, 'set')) {
            throw new Exception('Container method not found');
        }

        return $this->container->set($name, $value);
    }

    /**
     * Return database conection.
     *
     * @return \Doctrine\DBAL\Connection
     * @throws Exception
     */
    public function database()
    {
        if (!$this->has(DatabaseInterface::class)) {
            throw new Exception('Database interface not defined');
        }

        return $this->get(DatabaseInterface::class);
    }
}
