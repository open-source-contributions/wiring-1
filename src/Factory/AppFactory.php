<?php

namespace Wiring\Factory;

use Wiring\Middleware\InvolkerMiddleware;

class AppFactory extends InvolkerMiddleware implements ApplicationInterface
{
    /**
     * Starting application.
     */
    public function run()
    {
        $this->invoker();
    }

    /**
     * Stopping application.
     */
    public function stop()
    {
        $this->setIsAfterMiddleware(true);
        $this->invoker();
    }
}
