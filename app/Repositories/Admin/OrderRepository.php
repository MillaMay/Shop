<?php
/**
 * Created by PhpStorm.
 * User: Eva
 * Date: 24.06.2020
 * Time: 10:07
 */

namespace App\Repositories\Admin;

use App\Models\Admin\Order;
use App\Repositories\CoreRepository;
use App\Models\Admin\Order as Model;
use phpDocumentor\Reflection\Types\True_;

class OrderRepository extends CoreRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getModelClass()
    {
        return Model::class;
    }

    public function getAllOrders($perpage)
    {
        $orders = $this->startConditions()::withTrashed()
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select('orders.id', 'orders.user_id', 'orders.status', 'orders.created_at', 'orders.updated_at',
                'orders.currency', 'orders.note', 'users.name', 'users.phone')
            ->groupBy('orders.id')->orderBy('orders.status')->orderBy('orders.id', 'DESC')->toBase()->paginate($perpage);

        return $orders;
    }

    public function getOneOrder($id)
    {
        $order = $this->startConditions()::withTrashed()
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select('orders.*', 'users.name', 'users.phone')
            ->where('orders.id', $id)
            ->groupBy('orders.id')->first();

        return $order;
    }

    public function getAllOrderProducts($order_id)
    {
        $order_products = \DB::table('order_products')
            ->join('products', 'order_products.product_id', '=', 'products.id')
            ->select('order_products.*', 'products.title', 'products.price', 'products.count', 'products.id AS prod_id')
            ->where('order_id', $order_id)->get();

        return $order_products;
    }

    public function changeStatusOrder($id)
    {
        $item = $this->getId($id);
        if (!$item) {
            abort(404);
        }
        $order_prod = $this->getAllOrderProducts($id);
        if ($_GET['status'] == '1') {
            foreach ($order_prod as $prod) {
                if ($prod->count < $prod->qty) {
                    return false;
                }
                if ($prod->count > $prod->qty || $prod->count == $prod->qty) {
                    $products = $this->getAllOrderProducts($id);
                    foreach ($products as $prod2) {
                        $difference = $prod2->count - $prod2->qty;
                        $prod_count = \DB::table('products')->where('id', $prod2->product_id)->update(['count' => $difference]);
                        if ($difference == 0) {
                            \DB::table('products')->where('id', $prod2->product_id)->update(['status' => '0']);
                        }
                    }
                    if ($prod_count) {
                        $item->status = !empty($_GET['status']) ? '1' : '0';
                        $item->update();
                        return true;
                    }
                }
            }
        }
        if ($_GET['status'] == '0') {
            foreach ($order_prod as $prod3) {
                $difference2 = $prod3->count + $prod3->qty;
                $count_prod = \DB::table('products')->where('id', $prod3->product_id)->update(['count' => $difference2]);
                if ($difference2 > 0) {
                    \DB::table('products')->where('id', $prod3->product_id)->update(['status' => '1']);
                }
            }
            if ($count_prod) {
                $item->status = !empty($_GET['status']) ? '1' : '0';
                $item->update();
                return true;
            }
        }
    }

    public function changeStatusOnDelete($id)
    {
        $item = $this->getId($id);
        if (!$item) {
            abort(404);
        }
        $item->status = '2';
        $result = $item->update();

        return $result;
    }

    public function changeStatusOnRestore($id) // Метод восстановления Заказа после отмены
    {
        Order::withTrashed()->restore();
        $item = $this->getId($id);

        if (!$item) {
            abort(404);
        }
        $item->status = '0';
        $result = $item->update();

        return $result;
    }

    public function saveOrderComment($id)
    {
        $item = $this->getId($id);
        if (!$item) {
            abort(404);
        }
        $item->note = !empty($_POST['comment']) ? $_POST['comment'] : null;
        $result = $item->update();

        return $result;
    }
}