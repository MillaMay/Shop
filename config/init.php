<?php

    // Константы можно было прописать в файле .env и использовать хелпер env()

    if (!defined('ROOT')) define('ROOT', dirname(__DIR__));
    if (!defined('WWW')) define('WWW', ROOT . '/public');
    if (!defined('APP')) define('APP', ROOT . '/app');
    if (!defined('CORE')) define('CORE', ROOT . '/resources');
    if (!defined('LIBS')) define('LIBS', ROOT . '/resources/libs');
    if (!defined('CACHE')) define('CACHE', ROOT . '/tmp/cache');
    if (!defined('CONF')) define('CONF', ROOT . '/config');
    if (!defined('LAYOUT')) define('LAYOUT', 'app/blade.php');
    if (!defined('GALLERY')) define('GALLERY', '/public/uploads/gallery');
    if (!defined('IMG')) define('IMG', '/public/uploads/single');

    // То, что ниже - можно было передать через роуты: -------------------------

    $allowed_hosts = 'http://shop/index.blade.php';
    //dd($allowed_hosts); //"http://shop/index.php"

    $app_path = preg_replace("#[^/]+$#", '', $allowed_hosts);
    //dd($app_path); //"http://shop/"
    $app_path = preg_replace('/public/', '', $app_path);

    //--------------------------------------------------------------------------

    if (!defined('PATH')) define('PATH', $app_path);
    if (!defined('ADMIN')) define('ADMIN', PATH . 'admin/index');