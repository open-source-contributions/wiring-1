<?php

namespace Wiring\Exception;

class NotFoundException extends HttpException
{
    /**
     * NotFoundException constructor.
     *
     * @param string $message
     * @param \Exception|null $previous
     * @param int $code
     */
    public function __construct($message = 'Not Found', \Exception $previous = null, $code = 0)
    {
        parent::__construct($message, 404, $previous, [], $code);
    }
}
