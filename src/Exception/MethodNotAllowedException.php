<?php

namespace Wiring\Exception;

class MethodNotAllowedException extends \Exception
{
    /**
     * MethodNotAllowed exception.
     *
     * @param null $message
     */
    public function __construct($message = null)
    {
        if (is_null($message)) {
            $message = 'Method Not Allowed';
        }

        parent::__construct($message, 405);
    }
}