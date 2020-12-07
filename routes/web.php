<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', 'Shop\MainController@index')->name('index');

Route::get('/currency/{code}', 'Shop\MainController@changeCurrency')->name('currency');

Route::get('/search', 'Shop\MainController@searchResult');
Route::get('/autocomplete', 'Shop\MainController@search');

Route::group(['prefix' => 'basket'], function () {
    Route::post('/add/{product}', 'Shop\BasketController@basketAdd')->name('basket-add');
    Route::group(['middleware' => 'basket_empty'], function () {
        Route::get('/', 'Shop\BasketController@basket')->name('basket');
        //Route::get('/place', 'Shop\BasketController@basketPlace')->name('basket-place');
        Route::post('/remove/{product}', 'Shop\BasketController@basketRemove')->name('basket-remove');
        Route::post('/confirm', 'Shop\BasketController@basketConfirm')->name('basket-confirm');
    });
});

Route::get('/category/{category}', 'Shop\MainController@category')->name('category');
Route::get('/category/{category}/filters', 'Shop\MainController@filtersCategory')->name('category-filters');
Route::get('/filters', 'Shop\MainController@filters')->name('filters');
Route::get('/all-products', 'Shop\MainController@allProducts')->name('all-products');
Route::get('/product/{product}', 'Shop\MainController@product')->name('product');

Route::post('subscription/{product}', 'Shop\MainController@subscribe')->name('subscription');



/* home после "/" только для авторизованных пользователей */
Auth::routes(['verify' => true]);
Route::get('/home', 'Shop\MainController@index')->middleware('verified')->name('home');

#Admin side:
Route::group(['middleware' => ['status', 'auth']], function(){
    $groupData = [
        'namespace' => 'Shop\Admin',
        'prefix' => 'admin',
    ];

    Route::group($groupData, function(){
        Route::resource('index', 'MainController');

        Route::resource('orders', 'OrderController');
        Route::get('/orders/change/{id}', 'OrderController@change')->name('orders.change');
        Route::post('/orders/save/{id}', 'OrderController@save')->name('orders.save');
        Route::get('/orders/forcedestroy/{id}', 'OrderController@forcedestroy')->name('orders.forcedestroy');
        Route::get('/orders/changerestore/{id}', 'OrderController@changerestore')->name('orders.changerestore');

        //Этот метод почему-то должен быть выше следующего (тогда работает):
        Route::get('/categories/mydelete', 'CategoryController@mydelete')->name('categories.mydelete');
        Route::resource('categories', 'CategoryController');

        Route::resource('users', 'UserController');

        Route::get('/products/related', 'ProductController@related');
        Route::match(['get', 'post'], '/products/ajax-image-upload', 'ProductController@ajaxImage');
        Route::delete('/products/ajax-remove-image/{filename}', 'ProductController@deleteImage');
        Route::post('/products/gallery', 'ProductController@gallery');
        Route::post('/products/delete-gallery', 'ProductController@deleteGallery');
        Route::get('/products/return-status/{id}', 'ProductController@returnstatus')->name('products.returnstatus');
        Route::get('/products/delete-status/{id}', 'ProductController@deletestatus')->name('products.deletestatus');
        Route::get('/products/delete-product/{id}', 'ProductController@deleteproduct')->name('products.deleteproduct');

        Route::get('filter/groups-filter', 'FilterController@attributeGroup');
        Route::match(['get', 'post'], '/filter/groups-add-group', 'FilterController@groupAdd');
        Route::match(['get', 'post'], '/filter/group-edit/{id}', 'FilterController@groupEdit');
        Route::get('/filter/group-delete/{id}', 'FilterController@groupDelete');
        Route::get('/filter/values-filter', 'FilterController@attributeValue');
        Route::match(['get', 'post'], '/filter/values-add-value', 'FilterController@valueAdd');
        Route::match(['get', 'post'], '/filter/value-edit/{id}', 'FilterController@valueEdit');
        Route::get('/filter/value-delete/{id}', 'FilterController@valueDelete');

        Route::get('/currency/index', 'CurrencyController@index');
        Route::match(['get', 'post'], '/currency/add', 'CurrencyController@add');
        Route::match(['get', 'post'], '/currency/edit/{id}', 'CurrencyController@edit');
        Route::get('/currency/delete/{id}', 'CurrencyController@delete');

        Route::get('/search/result', 'SearchController@index');
        Route::get('/autocomplete', 'SearchController@search'); // скрипт в app_admin.blade

        Route::resource('products', 'ProductController');

    });
});

#User side:
Route::get('user/index/{id}', 'Shop\User\MainController@index')->name('users');
