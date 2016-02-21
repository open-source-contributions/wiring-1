<?php

namespace Wiring\Exception;

class MethodNotFoundException extends \Exception
{
    /**
     * NotFound exception.
     *
     * @param null $message
     */
    public function __construct($message = null)
    {
        if (is_null($message)) {
            $message = 'Not Found';
        }

        parent::__construct($message, 404);
    }
}
