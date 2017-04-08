<?php

namespace Wiring\Interfaces;

interface ApplicationInterface
{
    /**
     * Starting application.
     */
    public function run();

    /**
     * Stopping application.
     */
    public function stop();
}
