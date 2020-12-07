<?php
/**
 * Created by PhpStorm.
 * User: Eva
 * Date: 16.06.2020
 * Time: 14:43
 */

namespace App\CShop\Core;


class Registry
{

    use TSingletone;

    protected static $properties = [];

    public function setProperty($name, $value)
    {
        self::$properties[$name] = $value;
    }

    public function getProperty($name)
    {
        if (isset(self::$properties[$name])) {
            return self::$properties[$name];
        }
        return null;
    }

    public function getProperties()
    {
        return self::$properties;
    }

}