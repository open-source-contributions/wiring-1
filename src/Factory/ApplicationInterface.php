<?php

namespace Wiring\Factory;

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