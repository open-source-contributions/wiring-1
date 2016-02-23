<?php

namespace Wiring\Exception;

class MethodNotFoundException extends \Exception
{
    /**
     * Method Not Found exception.
     *
     * @param string $message
     */
    public function __construct($message = 'Not Found')
    {
        parent::__construct($message, 404);
    }
}
