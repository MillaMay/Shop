<?php

namespace App\Http\Controllers\Shop;

use App\Models\Admin\Currency;
use App\Models\Admin\Order;
use App\Models\Admin\Product;
use Illuminate\Http\Request;
use MetaTag;

class BasketController extends ShopBaseController
{
    public function basket()
    {
        $order_id = session('order_id');
        $order = Order::findOrFail($order_id);

        $user_id = $this->getUserId();
        $menu = $this->getMenuCategories();
        $currency = $this->getBaseCurrency();
        $currency_all = $this->getAllCurrency();
        MetaTag::setTags(['title' => "Basket", 'h1' => "My Shopping Basket"]);
        return view('shop.basket', compact('menu', 'currency', 'user_id', 'order', 'currency_all'));
    }

    public function basketAdd(Product $product)
    {
        $order_id = session('order_id');
        if(is_null($order_id)) {
            $order = Order::create();
            session(['order_id' => $order->id]);
        } else {
            $order = Order::findOrFail($order_id);
        }
        if ($order->products->contains($product->id)) {
            $pivotRow = $order->products()->where('product_id', $product->id)->first()->pivot;

            //dd($pivotRow, $product->count); // Чтобы увидеть этот dd(), нужно сначало 1 раз добавить товар в корзину
            // Это для того, если я сделаю проверку наличия кол-ва товара для заказа на стороне клиента

            $pivotRow->qty++;
            $pivotRow->update();
        } else {
            $order->products()->attach($product->id);
        }
        return redirect()->route('basket');
    }

    public function basketRemove(Product $product)
    {
        $order_id = session('order_id');
        $order = Order::findOrFail($order_id);

        if ($order->products->contains($product->id)) {
            $pivotRow = $order->products()->where('product_id', $product->id)->first()->pivot;
            if ($pivotRow->qty < 2) {
                $order->products()->detach($product->id);
            } else {
                $pivotRow->qty--;
                $pivotRow->update();
            }
        }
        return redirect()->route('basket');
    }

    public function basketConfirm()
    {
        $order_id = session('order_id');
        $order = Order::findOrFail($order_id);
        $currency = Currency::where('base', '1')->first();
        if (\Auth::check() === true) {
            //$email = \Auth::user()->email; // получение email юзера
            $order->user_id = \Auth::id();
            $order->currency = $currency->code;
            $result = $order->save();
            if ($result) {
                session()->flash('info', 'Your order is accepted for processing!');
            }
            session()->forget('order_id');
            return redirect()->route('index');
        } else {
            return redirect()->route('login'); // Как вернуть на текущую (basket) страницу после логина?
        }
    }

//    public function basketPlace()
//    {
//        $user_id = $this->getUserId();
//        $menu = $this->getMenuCategories();
//        $currency = $this->getBaseCurrency();
//        MetaTag::setTags(['title' => "Confirm Order", 'h1' => ""]);
//        return view('shop.place-order', compact('menu', 'currency', 'user_id'));
//    }

}
