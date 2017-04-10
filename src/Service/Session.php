<?php

namespace Wiring\Service;

class Session
{
    /**
     * Get session.
     *
     * @param $key
     * @param null $default
     * @return null
     */
    public static function get($key, $default = null)
    {
        if (self::exists($key)) {
            return $_SESSION[$key];
        }

        return $default;
    }

    /**
     * Set session.
     *
     * @param $name
     * @param $value
     * @return mixed
     */
    public static function set($name, $value)
    {
        return $_SESSION[$name] = $value;
    }

    /**
     * Check session exists.
     *
     * @param $key
     * @return bool
     */
    public static function exists($key)
    {
        return (isset($_SESSION[$key])) ? true : false;
    }

    /**
     * Remove session.
     *
     * @param $key
     */
    public static function destroy($key)
    {
        if (self::exists($key)) {
            unset($_SESSION[$key]);
        }
    }
}
