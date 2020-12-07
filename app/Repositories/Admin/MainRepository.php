<?php
/**
 * Created by PhpStorm.
 * User: Eva
 * Date: 23.06.2020
 * Time: 18:26
 */

namespace App\Repositories\Admin;

use App\Repositories\CoreRepository;
use Illuminate\Database\Eloquent\Model;

class MainRepository extends CoreRepository
{
    protected function getModelClass()
    {
        return Model::class;
    }

    public static function getCountOrders()
    {
        $orders = \DB::table('orders')->where('status', '0')->get()->count();
        return $orders;
    }

    public static function getCountUsers()
    {
        $users = \DB::table('users')->get()->count();
        return $users;
    }

    public static function getCountProducts()
    {
        $products = \DB::table('products')->get()->count();
        return $products;
    }

    public static function getCountCategories()
    {
        $categories = \DB::table('categories')->get()->count();
        return $categories;
    }
}