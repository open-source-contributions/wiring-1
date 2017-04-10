<?php

namespace App\Provider;

class Cookie
{
    /**
     * Get cookie.
     *
     * @param $name
     * @return mixed
     */
    public static function get($name)
    {
        return $_COOKIE[$name];
    }

    /**
     * Set cookie.
     *
     * @param $name
     * @param $value
     * @param $expiry
     * @param bool $secure
     * @return bool
     */
    public static function set($name, $value, $expiry, $secure = false)
    {
        if (setcookie($name, $value, $expiry, '/', null, $secure, true)) {
            return true;
        }

        return false;
    }

    /**
     * Check cookie exists.
     *
     * @param $name
     * @return bool
     */
    public static function exists($name)
    {
        return (isset($_COOKIE[$name])) ? true : false;
    }

    /**
     * Remove cookie.
     *
     * @param $name
     */
    public static function destroy($name)
    {
        self::set($name, '', time() - 1);
    }
}
