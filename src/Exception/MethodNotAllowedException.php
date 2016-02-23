<?php

namespace Wiring\Exception;

class MethodNotAllowedException extends \Exception
{
    /**
     * Method Not Allowed exception.
     *
     * @param string $message
     */
    public function __construct($message = 'Method Not Allowed')
    {
        parent::__construct($message, 405);
    }
}
