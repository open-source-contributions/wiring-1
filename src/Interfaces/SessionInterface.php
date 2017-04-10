<?php

namespace Wiring\Interfaces;

interface SessionInterface
{
    /**
     * Get session.
     *
     * @param $key
     * @param null $default
     * @return null
     */
    public static function get($key, $default = null);

    /**
     * Set session.
     *
     * @param $name
     * @param $value
     * @return mixed
     */
    public static function set($name, $value);

    /**
     * Check session exists.
     *
     * @param $key
     * @return bool
     */
    public static function exists($key);

    /**
     * Remove session.
     *
     * @param $key
     */
    public static function destroy($key);
}
