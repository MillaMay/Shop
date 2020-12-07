<?php

namespace App\Http\Controllers\Shop;

use App\Http\Requests\SubscriptionRequest;
use App\Models\Admin\Category;
use App\Models\Admin\Currency;
use App\Models\Admin\Product;
use App\Models\Subscription;
use App\Repositories\Admin\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use MetaTag;

class MainController extends ShopBaseController
{
    public function index(Request $request)
    {
        //dd($request->all());
        //dd(get_class_methods(\Validator::class));

        //\App\Services\CurrencyValue::getValue();// проверка dd($url) в App\Services\CurrencyValue +
        // обновляет каждый раз курс, если обновляется страница

        // Теперь в логах будут отображаться ip-адреса клиентов и время посещения ими сайта
        //Log::channel('single')->info($request->ip()); // Вместо info() можно debug(), но отличия никакого

        \Debugbar::info($request);
        // Когда будет на продакшене, надо будет Debugbar "проверить" и не забыть про APP_DEBUG=true в .env

        $products = Product::where('status', '=', '1')->orderBy('id', 'DESC')->limit(3)->get(); // по 3

        $user_id = $this->getUserId();
        $menu = $this->getMenuCategories();
        $currency = $this->getBaseCurrency();
        $currency_all = $this->getAllCurrency();
        MetaTag::setTags(['title' => "Shop Luxury Watches", 'h1' => "SHOP LUXURY WATCHES"]);
        return view('shop.index', ['menu' => $menu,
            'products' => $products, 'currency' => $currency, 'user_id' => $user_id, 'currency_all' => $currency_all]);
    }

    public function searchResult(Request $request)
    {
        $query = !empty(trim($request->search)) ? trim($request->search) : null;

        /*Здесь также было обращение к товарам через \DB::table('products')
        Пришлось изменить обращение через модель, чтобы сработала конвертация для currency
        Потому что именно в модели Product вызывается метод конвертации*/
        $products = Product::where('title', 'LIKE', '%' .$query. '%')
            ->orWhere('keywords', 'LIKE', '%' .$query. '%')
            ->paginate(3); // по 3

        // Если поиск непосредственно 1 товара, то при нахождении, сразу кидает на его страницу:
        if ($products->count() == 1 && !$request->page) {
            $products = \DB::table('products')
                ->where('title', 'LIKE', '%' .$query. '%')
                ->orWhere('keywords', 'LIKE', '%' .$query. '%')->first();
            return redirect()->route('product', $products->id);
        }
        if ($products->count() == 0) {
            session()->now('info', 'There are no products to your request');
            // session()->now в отличии от session()->flash при следущем запросе уничтожается
        }

        $user_id = $this->getUserId();
        $menu = $this->getMenuCategories();
        $currency = $this->getBaseCurrency();
        $currency_all = $this->getAllCurrency();
        MetaTag::setTags(['title' => "Search", 'h1' => "Search results"]);
        return view('shop.search', compact('query', 'products', 'menu', 'currency', 'user_id', 'currency_all'));
    }

    public function search(Request $request)
    {
        $search = $request->get('term');
        $result = \DB::table('products')->select('title')
            ->where('title', 'LIKE', '%' .$search. '%')->pluck('title');

        return response()->json($result);
    }

    public function category(Request $request, $category_id)
    {
        $category = Category::where('id', $category_id)->first();
        if (empty($category)) {
            abort(404);
        }
        $products = Product::where([['category_id', $category_id], ['status', '=', '1']])->paginate(9); // по 9

        // Проверка наличия товаров в категории:
        $categoryRepository = app(CategoryRepository::class);
        $parents = $categoryRepository->checkProductParents($category_id);
        if (!$parents) {
            return back()->withErrors(['msg' => "There are no products in category $category->title yet ..."]);
        }

        $user_id = $this->getUserId();
        $menu = $this->getMenuCategories();
        $currency = $this->getBaseCurrency();
        $currency_all = $this->getAllCurrency();
        MetaTag::setTags(['title' => "$category->title", 'h1' => "Products from category $category->title"]);
        return view('shop.category', compact('category', 'menu', 'currency', 'user_id', 'products', 'currency_all'));
    }

    public function filtersCategory(Request $request, $category_id)
    {
        $category = Category::where('id', $category_id)->first();
        if (empty($category)) {
            abort(404);
        }
        $productQuery = Product::query();

        if ($request->filled('price_from')) {
            $productQuery->where([['price', '>=', $request->price_from], ['category_id', '=', $category_id],]);
            // Массив в where() вместо оператора AND
        }
        if ($request->filled('price_to')) {
            $productQuery->where([['price', '<=', $request->price_to], ['category_id', '=', $category_id],]);
        }

        $validator = \Validator::make($request->only('price_from'), [
            'price_from' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
        ],
            [
                'price_from.regex' => 'The price can be either an integer or a floating point number',
            ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $validator = \Validator::make($request->only('price_to'), [
            'price_to' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
        ],
            [
                'price_to.regex' => 'The price can be either an integer or a floating point number',
            ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $request_attrs = $request->input('attrs');

        if ($request_attrs) {
            foreach ($request_attrs as $value) {

                $filters_id = [];
                foreach ($value as $val) {
                    $filters_id[] = \DB::table('attribute_values')
                        ->select('id')
                        ->where('id', $val)->pluck('id')->first();
                }

                $products_id = \DB::table('attribute_products')->select('product_id')
                    ->whereIn('attr_id', $filters_id)->pluck('product_id')->all();

                $productQuery->whereIn('id', $products_id)->where([['category_id', $category_id],
                    ['status', '=', '1']]);
            }
        }
        $products = $productQuery->paginate(6); // по 6
        if ($products->count() == 0) {
            session()->now('info', 'There are no products with the selected characteristics');
        }

        $user_id = $this->getUserId();
        $menu = $this->getMenuCategories();
        $currency = $this->getBaseCurrency();
        $currency_all = $this->getAllCurrency();
        MetaTag::setTags(['title' => "$category->title", 'h1' => "Products from category $category->title"]);
        return view('shop.category', compact('category', 'menu', 'currency', 'user_id', 'products', 'currency_all'));
    }

    public function filters(Request $request)
    {
        $productQuery = Product::query();

        if ($request->filled('price_from')) {
            $productQuery->where('price', '>=', $request->price_from);
        }
        if ($request->filled('price_to')) {
            $productQuery->where('price', '<=', $request->price_to);
        }
        //if ($request->price_from != null) { ->Вместо этого указано nullable
            $validator = \Validator::make($request->only('price_from'), [
                    'price_from' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
                ],
                [
                    'price_from.regex' => 'The price can be either an integer or a floating point number',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        //}
        //if ($request->price_to != null) { ->Вместо этого указано nullable
            $validator = \Validator::make($request->only('price_to'), [
                'price_to' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
                ],
                [
                    'price_to.regex' => 'The price can be either an integer or a floating point number',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        //}
        $request_attrs = $request->input('attrs');

        if ($request_attrs) {
            foreach ($request_attrs as $value) {

                $filters_id = [];
                foreach ($value as $val) {
                    $filters_id[] = \DB::table('attribute_values')
                        ->select('id')
                        ->where('id', $val)->pluck('id')->first();
                }

                $products_id = \DB::table('attribute_products')->select('product_id')
                    ->whereIn('attr_id', $filters_id)->pluck('product_id')->all();

                $productQuery->whereIn('id', $products_id)->where('status', '=', '1');
            }
        }
        $products = $productQuery->paginate(6); // по 6
        if ($products->count() == 0) {
            session()->now('info', 'There are no products with the selected characteristics');
        }

        $user_id = $this->getUserId();
        $menu = $this->getMenuCategories();
        $currency = $this->getBaseCurrency();
        $currency_all = $this->getAllCurrency();
        MetaTag::setTags(['title' => "All Products", 'h1' => "All Products"]);
        return view('shop.all-products', compact('products', 'user_id', 'menu', 'currency', 'currency_all'));
    }

    public function allProducts()
    {
        $products = Product::where('status', '=', '1')->orderBy('id')->paginate(9); // по 9

        $user_id = $this->getUserId();
        $menu = $this->getMenuCategories();
        $currency = $this->getBaseCurrency();
        $currency_all = $this->getAllCurrency();
        MetaTag::setTags(['title' => "All Products", 'h1' => "All Products"]);
        return view('shop.all-products', compact('products', 'user_id', 'menu', 'currency', 'currency_all'));
    }

    public function product($product_id)
    {
        $prod = Product::where('id', $product_id)->first();
        if (empty($prod)) {
            abort(404);
        }
        $prods = $this->getProductRepository()->getRelatedProducts($product_id);
        $products = [];
        foreach ($prods as $product) {
            if ($product->status == 1) {
                $products[] = $product;
            }
        }
        $gallery = $this->getProductRepository()->getGallery($product_id);
        $filters = $this->getProductRepository()->getFilterProduct($product_id);

        //Пока 5 ступеней вложенности для показа:
        $category = Category::where('id', $prod->category_id)->first();
        // Вместо where()->first() можно использовать просто find()

        // Условия для оптимизации запроса к модели Category:
        $category->parent_id ? $parent2_category = Category::find($category->parent_id ?? '') : $parent2_category = '';
        $parent2_category ? $parent3_category = Category::find($parent2_category->parent_id ?? '') : $parent3_category = '';
        $parent3_category ? $parent4_category = Category::find($parent3_category->parent_id ?? '') : $parent4_category = '';
        $parent4_category ? $parent5_category = Category::find($parent4_category->parent_id ?? '') : $parent5_category = '';

        $user_id = $this->getUserId();
        $menu = $this->getMenuCategories();
        $currency = $this->getBaseCurrency();
        $currency_all = $this->getAllCurrency();
        MetaTag::setTags(['title' => "$prod->title", 'h1' => ""]);
        return view('shop.product', compact('menu', 'currency', 'user_id', 'prod', 'gallery',
            'category', 'parent2_category', 'parent3_category', 'parent4_category', 'parent5_category', 'products',
            'filters', 'currency_all'));
    }

    public function subscribe(SubscriptionRequest $request, Product $product)
    {
        Subscription::create([
            'email' => $request->email,
            'product_id' => $product->id,
        ]);

        return redirect()->back()->withErrors( 'Thanks! You will receive a message about the arrival of this product');
        // здесь withErrors работает и для SubscriptionRequest
    }

    public function changeCurrency($code)
    {
        $currency = Currency::byCode($code)->firstOrFail();
        session(['currency' => $currency->code]);
        return redirect()->back();
    }
}
