<?php

namespace Wiring\Platform\RasPi;

class GpioException extends \RuntimeException
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @param string $message
     * @param integer $code
     * @param \Exception|null $previous
     * @param string $path
     */
    public function __construct($message, $code = 0, \Exception $previous = null, $path = null)
    {
        parent::__construct($message, $code, $previous);

        $this->path = $path;
    }

    /**
     * @return null|string
     */
    public function getPath()
    {
        return $this->path;
    }
}
