<?php

namespace App\Http\Middleware;

use App\Models\Admin\Order;
use Closure;

class BasketIsNotEmpty
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $order_id = session('order_id');
        if (!is_null($order_id)) {
            $order = Order::findOrFail($order_id);
            if ($order->products->count() > 0) {
                return $next($request);
            }
        }
        session()->flash('info', 'Your basket is empty!');
        return redirect()->route('index');
    }
}
