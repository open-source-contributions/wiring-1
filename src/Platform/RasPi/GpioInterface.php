<?php

namespace Wiring\Platform\RasPi;

interface GpioInterface
{
    const INPUT = 'in';
    const OUTPUT = 'out';

    const IO_ON = 1;
    const IO_OFF = 0;

    const PATH_GPIO = '/sys/class/gpio/gpio';
    const PATH_GPIO_EXPORT = '/sys/class/gpio/export';
    const PATH_GPIO_UNEXPORT = '/sys/class/gpio/unexport';

    /**
     * Set pin number and direction.
     *
     * @param int $pinNo
     * @param string $direction
     *
     * @return GpioInterface
     */
    public function pinMode($pinNo, $direction);

    /**
     * Get input value.
     *
     * @param int $pinNo
     *
     * @return string GPIO value or boolean false
     */
    public function pinRead($pinNo);

    /**
     * Set output value.
     *
     * @param int $pinNo
     * @param string $value
     *
     * @return GpioInterface
     */
    public function pinWrite($pinNo, $value);

    /**
     * Unexport Pin.
     *
     * @param int $pinNo
     *
     * @return GpioInterface
     */
    public function pinUnexport($pinNo);

    /**
     * Unexport all pins.
     *
     * @return GpioInterface
     */
    public function pinUnexportAll();

    /**
     * Get the pin's current direction.
     *
     * @param int $pinNo
     *
     * @return string string pin's direction value or boolean false
     */
    public function getCurrentDirection($pinNo);

    /**
     * Check if pin is exported.
     *
     * @param int $pinNo
     *
     * @return boolean
     */
    public function isExported($pinNo);

    /**
     * Check for valid direction, in or out.
     *
     * @param string $direction
     *
     * @return boolean
     */
    public function isValidDirection($direction);

    /**
     * Check for valid output value.
     *
     * @param mixed $output
     *
     * @return boolean
     */
    public function isValidOutput($output);

    /**
     * Check for valid pin value.
     *
     * @param int $pinNo
     * @param bool $exception
     *
     * @return boolean
     */
    public function isValidPin($pinNo, $exception = false);
}
