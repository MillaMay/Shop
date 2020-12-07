<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use App\Models\Admin\Currency;
use App\Repositories\Admin\CategoryRepository;
use App\Repositories\Admin\FilterValueRepository;
use App\Repositories\Admin\ProductRepository;

abstract class ShopBaseController extends Controller
{
    protected function getMenuCategories()
    {
        $categoryRepository = app(CategoryRepository::class);
        $arrMenu = Category::all();
        $menu = $categoryRepository->buildMenu($arrMenu);

        return $menu;
    }

    protected function getBaseCurrency()
    {
        $currency = \DB::table('currencies')
            ->where('base', '=', '1')->first();

        return $currency;
    }

    protected function getAllCurrency()
    {
        $currency_all = Currency::where('code', '=', 'USD')->orWhere('code', '=', 'RUB')->get();
        // пока только доллары и рубли

        return $currency_all;
    }

    protected function getUserId()
    {
        if (\Auth::check()) {
            $user_id = \Auth::user()->id;
        } else {
            $user_id = false;
        }

        return $user_id;
    }

    protected function getProductRepository()
    {
        $productRepository = app(ProductRepository::class);

        return $productRepository;
    }

    protected function getFilterValueRepository()
    {
        $filterRepository = app(FilterValueRepository::class);

        return $filterRepository;
    }
}
