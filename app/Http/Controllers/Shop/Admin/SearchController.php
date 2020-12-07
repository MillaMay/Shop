<?php

namespace App\Http\Controllers\Shop\Admin;

use Illuminate\Http\Request;
use MetaTag;

class SearchController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $query = !empty(trim($request->search)) ? trim($request->search) : null;
        $products = \DB::table('products')
            ->where('title', 'LIKE', '%' .$query. '%')
            ->get()->all();
        $currency = \DB::table('currencies')
            ->where('base', '=', '1')->first();

        MetaTag::setTags(['title' => "Запрос: " .$request->search]);
        return view('shop.admin.search.result', compact('query', 'products', 'currency'));
    }

    public function search(Request $request)
    {
        $search = $request->get('term'); // term for script in app_admin.blade
        $result = \DB::table('products')->select('title')
            ->where('title', 'LIKE', '%' .$search. '%')->pluck('title');

        return response()->json($result);
    }
}
