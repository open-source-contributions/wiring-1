<?php

namespace Wiring\Exception;

class MethodNotAllowedException extends HttpException
{
    /**
     * MethodNotAllowedException constructor.
     *
     * @param array $allowed
     * @param string $message
     * @param \Exception|null $previous
     * @param int $code
     */
    public function __construct(array $allowed = [], $message = 'Method Not Allowed',
                                \Exception $previous = null, $code = 0)
    {
        $headers = [
            'Allow' => implode(', ', $allowed)
        ];

        parent::__construct($message, 405, $previous, $headers, $code);
    }
}
