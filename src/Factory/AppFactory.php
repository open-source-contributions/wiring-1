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
}
