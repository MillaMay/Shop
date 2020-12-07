<?php
/**
 * Created by PhpStorm.
 * User: Eva
 * Date: 16.06.2020
 * Time: 14:52
 */

namespace App\CShop\Core;


class ShopApp
{

    public static $app;

    public static function get_instance()
    {
        self::$app = Registry::instance();
        self::getParams();
        return self::$app;
    }

    protected static function getParams()
    {
        $params = require CONF . '/params.php';

         if (!empty($params)) {
             foreach ($params as $k => $v) {
                 self::$app->setProperty($k, $v);
             }
         }
    }

}