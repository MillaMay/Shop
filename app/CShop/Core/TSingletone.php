<?php
/**
 * Created by PhpStorm.
 * User: Eva
 * Date: 16.06.2020
 * Time: 12:18
 */

namespace App\CShop\Core;


trait TSingletone // Singletone -> Паттерн
{

   private static $instance;

   public static function instance()
   {
       if (self::$instance === null) {
           self::$instance = new self();
       }
       return self::$instance;
   }

}