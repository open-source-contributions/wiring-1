<?php

namespace Wiring\Controller;

use Exception;
use Psr\Container\ContainerInterface;
use Wiring\Interfaces\ApplicationInterface;
use Wiring\Interfaces\AuthInterface;
use Wiring\Interfaces\ConfigInterface;
use Wiring\Interfaces\ControllerInterface;
use Wiring\Interfaces\CsrfInterface;
use Wiring\Interfaces\DatabaseInterface;
use Wiring\Interfaces\FlashInterface;
use Wiring\Interfaces\HashInterface;
use Wiring\Interfaces\RouterInterface;

abstract class AbstractController implements ControllerInterface
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * Create container.
     *
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Get an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
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
     *
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
     *
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
     *
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
     * @param mixed $value Value, use definition helpers to define objects.
     *
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
     * Return application.
     *
     * @throws Exception
     *
     * @return \Wiring\Factory\AppFactory
     */
    public function app()
    {
        if (!$this->has(ApplicationInterface::class)) {
            throw new Exception('Application interface not defined');
        }

        return $this->get(ApplicationInterface::class);
    }

    /**
     * Get authentication.
     *
     * @throws Exception
     *
     * @return mixed
     */
    public function auth()
    {
        if (!$this->has(AuthInterface::class)) {
            throw new Exception('Auth interface not defined');
        }

        return $this->get(AuthInterface::class);
    }

    /**
     * Get settings properties.
     *
     * @param $key
     * @throws Exception
     *
     * @return mixed
     */
    public function config($key)
    {
        if (!$this->has(ConfigInterface::class)) {
            throw new Exception('Config interface not defined');
        }

        return $this->get(ConfigInterface::class)->get($key);
    }

    /**
     * Get CSRF protection.
     *
     * @throws Exception
     *
     * @return mixed
     */
    public function csrf()
    {
        if (!$this->has(CsrfInterface::class)) {
            throw new Exception('CSRF interface not defined');
        }

        return $this->get(CsrfInterface::class);
    }

    /**
     * Return database connection.
     *
     * @throws Exception
     *
     * @return \Wiring\Interfaces\DatabaseInterface
     */
    public function database()
    {
        if (!$this->has(DatabaseInterface::class)) {
            throw new Exception('Database interface not defined');
        }

        return $this->get(DatabaseInterface::class);
    }

    /**
     * Get flash messages.
     *
     * @param $type
     * @param $message
     * @throws Exception
     */
    public function flash($type, $message)
    {
        if (!$this->has(FlashInterface::class)) {
            throw new Exception('Flash interface not defined');
        }

        return $this->get(FlashInterface::class)->addMessage($type, $message);
    }

    /**
     * Get hash object.
     *
     * @throws Exception
     *
     * @return mixed
     */
    public function hash()
    {
        if (!$this->has(HashInterface::class)) {
            throw new Exception('Hash interface not defined');
        }

        return $this->get(HashInterface::class);
    }

    /**
     * Get message properties.
     *
     * @param $key
     * @return mixed
     */
    public function lang($key)
    {
        return $this->config("lang." . $key);
    }

    /**
     * Redirect.
     *
     * Note: This method is not part of the PSR-7 standard.
     *
     * This method prepares the response object to return an HTTP Redirect
     * response to the client.
     *
     * @param Psr\Http\Message\ResponseInterface
     * @param $url
     * @param $status
     *
     * @return \Psr\Http\Message\ResponseInterface $request
     */
    public function redirect($response, $url, $status = 200)
    {
        return $this->withRedirect($response, $url, $status);
    }

    /**
     * Output rendered template.
     *
     * @throws Exception
     *
     * @return mixed
     */
    public function router()
    {
        if (!$this->has(RouterInterface::class)) {
            throw new Exception('Router interface not defined');
        }

        return $this->get(RouterInterface::class);
    }

    /**
     * Response with redirect.
     *
     * @param $response \Psr\Http\Message\ResponseInterface
     * @param $url
     * @param null $status
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function withRedirect($response, $url, $status = null)
    {
        $responseWithRedirect = $response->withHeader('Location', (string)$url);

        if (is_null($status) && $response->getStatusCode() === 200) {
            $status = 302;
        }

        if (!is_null($status)) {
            return $responseWithRedirect->withStatus($status);
        }

        return $responseWithRedirect;
    }
}
