<?php

namespace Wiring\Factory;

use Wiring\Middleware\InvolkerMiddleware;
use Wiring\Middleware\MiddlewareInterface;

class AppFactory extends InvolkerMiddleware
{
    /**
     * Starting application.
     */
    public function run()
    {
        $this();
    }

    /**
     * Stopping application.
     */
    public function stop()
    {
        $this->setIsAfterMiddleware(true);
        $this();
    }

    /**
     * Add router middleware.
     *
     * @param \Wiring\Middleware\MiddlewareInterface $router
     * @return self
     */
    public function addRouterMiddleware(MiddlewareInterface $router)
    {
        $this->addMiddleware('router', $router);

        return $this;
    }

    /**
     * Add dispatcher middleware.
     *
     * @param \Wiring\Middleware\MiddlewareInterface $dispatcher
     * @return self
     */
    public function addDispatcherMiddleware(MiddlewareInterface $dispatcher)
    {
        $this->addMiddleware('dispatcher', $dispatcher);

        return $this;
    }

    /**
     * Add emitter middleware.
     *
     * @param \Wiring\Middleware\MiddlewareInterface $emitter
     * @return self
     */
    public function addEmitterMiddleware(MiddlewareInterface $emitter)
    {
        $this->addAfterMiddleware('emitter', $emitter);

        return $this;
    }
}
