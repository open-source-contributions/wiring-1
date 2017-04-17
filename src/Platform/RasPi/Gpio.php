<?php

namespace Wiring\Platform\RasPi;

class Gpio implements GpioInterface
{
    /**
     * @var array
     */
    protected $exportedPins;

    /**
     * @var array
     */
    protected $pins;

    /**
     * @var array
     */
    protected $directions = [
        GpioInterface::INPUT,
        GpioInterface::OUTPUT
    ];

    /**
     * @var array
     */
    protected $outputs = [
        GpioInterface::IO_ON,
        GpioInterface::IO_OFF
    ];

    /**
     * GPIO constructor.
     *
     * @param array $pins
     */
    public function __construct(array $pins)
    {
        foreach ($pins as $pin) {
            if (!is_int($pin)) {
                throw new \InvalidArgumentException(
                    sprintf('Pins list can only contains integer, %s found', gettype($pin))
                );
            }
        }

        if (empty($pins)) {
            throw new \InvalidArgumentException('Pins list must, at least, contains one pin');
        }

        $this->pins = $pins;
    }

    /**
     * Set pin number and direction.
     *
     * @param int $pinNo
     * @param string $direction
     *
     * @return $this
     */
    public function pinMode($pinNo, $direction)
    {
        $this->isValidDirection($direction, true);

        if ($this->isExported($pinNo)) {
            $this->pinUnexport($pinNo);
        }

        // Export pin and set direction
        $this->filePutContents(GpioInterface::PATH_GPIO_EXPORT, $pinNo);
        $this->filePutContents(GpioInterface::PATH_GPIO . $pinNo . '/direction', $direction);

        return $this;
    }

    /**
     * Get input value.
     *
     * @param int $pinNo
     *
     * @return string
     */
    public function pinRead($pinNo)
    {
        $this->isExported($pinNo, true);

        if (($dir = $this->getCurrentDirection($pinNo)) != GpioInterface::INPUT) {
            throw new \RuntimeException(
                sprintf('Direction "%s" is invalid, "%s" expected', $dir, GpioInterface::INPUT)
            );
        }

        return trim($this->fileGetContents(GpioInterface::PATH_GPIO . $pinNo . '/value'));
    }

    /**
     * Set output value.
     *
     * @param int $pinNo
     * @param string $value
     *
     * @return $this
     */
    public function pinWrite($pinNo, $value)
    {
        $this->isExported($pinNo, true);
        $this->isValidOutput($value, true);

        if (($dir = $this->getCurrentDirection($pinNo)) != GpioInterface::OUTPUT) {
            throw new \RuntimeException(
                sprintf('Direction "%s" is invalid, "%s" expected', $this->getCurrentDirection($pinNo),
                    GpioInterface::OUTPUT)
            );
        }

        $this->filePutContents(GpioInterface::PATH_GPIO . $pinNo . '/value', $value);

        return $this;

    }

    /**
     * Unexport Pin.
     *
     * @param int $pinNo
     *
     * @return $this
     */
    public function pinUnexport($pinNo)
    {
        if ($this->isExported($pinNo)) {
            $this->filePutContents(GpioInterface::PATH_GPIO_UNEXPORT, $pinNo);

            $this->exportedPins[$pinNo] = false;
        }

        return $this;
    }

    /**
     * Unexport all pins.
     *
     * @return $this
     */
    public function pinUnexportAll()
    {
        foreach ($this->pins as $pinNo) {
            $this->pinUnexport($pinNo);
        }

        return $this;
    }

    /**
     * Get the pin's current direction.
     *
     * @param int $pinNo
     *
     * @return string string pin's direction value or boolean false
     */
    public function getCurrentDirection($pinNo)
    {
        $this->isExported($pinNo, true);

        return trim($this->fileGetContents(GpioInterface::PATH_GPIO . $pinNo . '/direction'));
    }

    /**
     * Check if pin is exported.
     *
     * @param int $pinNo
     * @param bool $exception
     *
     * @return bool
     */
    public function isExported($pinNo, $exception = false)
    {
        $this->isValidPin($pinNo, true);

        if (!file_exists(GpioInterface::PATH_GPIO . $pinNo)) {
            if ($exception) {
                throw new \RuntimeException(sprintf('Pin "%s" not exported', $pinNo));
            }

            return false;
        }

        return true;
    }

    /**
     * Check for valid direction, in or out.
     *
     * @param string $direction
     * @param bool $exception
     *
     * @return bool
     */
    public function isValidDirection($direction, $exception = false)
    {
        if (!is_string($direction) || empty($direction)) {
            if ($exception) {
                throw new \InvalidArgumentException(
                    sprintf('Direction "%s" is invalid (string expected)', $direction)
                );
            }

            return false;
        }

        if (!in_array($direction, $this->directions)) {
            if ($exception) {
                throw new \InvalidArgumentException(
                    sprintf('Direction "%s" is invalid (unknown direction)', $direction)
                );
            }

            return false;
        }

        return true;
    }

    /**
     * Check for valid output value.
     *
     * @param mixed $output
     * @param bool $exception
     *
     * @return bool
     */
    public function isValidOutput($output, $exception = false)
    {
        if (!is_int($output)) {
            if ($exception) {
                throw new \InvalidArgumentException(
                    sprintf('Pin value "%s" is invalid (integer expected).', $output)
                );
            }

            return false;
        }

        if (!in_array($output, $this->outputs)) {
            if ($exception) {
                throw new \InvalidArgumentException(
                    sprintf('Output value "%s" is invalid (out of exepected range).', $output)
                );
            }

            return false;
        }

        return true;
    }

    /**
     * Check for valid pin value.
     *
     * @param int $pinNo
     * @param bool $exception
     *
     * @return bool
     */
    public function isValidPin($pinNo, $exception = false)
    {
        if (!is_int($pinNo)) {
            if ($exception) {
                throw new \InvalidArgumentException(
                    sprintf('Pin number "%s" is invalid (integer expected)', $pinNo)
                );
            }

            return false;
        }

        if (!in_array($pinNo, $this->pins)) {
            if ($exception) {
                throw new \InvalidArgumentException(
                    sprintf('Pin number "%s" is invalid (out of exepected range)', $pinNo)
                );
            }

            return false;
        }

        return true;
    }

    /**
     * Get RaspberryPi version.
     *
     * @return int|number Raspi version
     */
    public function getVersion()
    {
        $cpuinfo = preg_split("/\n/", file_get_contents('/proc/cpuinfo'));
        foreach ($cpuinfo as $line) {
            if (preg_match('/Revision\s*:\s*([^\s]*)\s*/', $line, $matches)) {
                return hexdec($matches[1]);
            }
        }

        return 0;
    }

    /**
     * Get file contents.
     *
     * @param $file
     *
     * @return bool|string
     */
    protected function fileGetContents($file)
    {
        if (($ret = @file_get_contents($file)) === false) {
            if (!is_readable($file)) {
                throw new GpioException(
                    sprintf('"%s" not readable, make sur required permissions are available', $file), 0, null, $file
                );
            }
            throw new GpioException(
                sprintf('Cannot read "%s" for an unkown reason', $file), 0, null, $file
            );
        }

        return $ret;
    }

    /**
     * Set file contents.
     *
     * @param $file
     * @param $data
     *
     * @return bool|int
     */
    protected function filePutContents($file, $data)
    {
        if (($ret = @file_put_contents($file, $data)) === false) {
            if (!is_writeable($file)) {
                throw new GpioException(
                    sprintf('"%s" not writable, make sur required permissions are available', $file), 0, null, $file
                );
            }
            throw new GpioException(
                sprintf('Cannot write "%s" for an unkown reason', $file), 0, null, $file
            );
        }

        return $ret;
    }
}
