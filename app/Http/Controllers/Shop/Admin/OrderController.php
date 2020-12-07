<?php

namespace App\Http\Controllers\Shop\Admin;

//use Illuminate\Http\Request;
use App\Http\Requests\AdminOrderSaveRequest;
use App\Models\Admin\Order;
use App\Repositories\Admin\MainRepository;
use App\Repositories\Admin\OrderRepository;


class OrderController extends AdminBaseController
{
    private $orderRepository; // Переменная для доступа

    public function __construct()
    {
        parent::__construct();
        $this->orderRepository = app(OrderRepository::class); // Доступ ко всем методам данного репозитория
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perpage = 10;
        $countOrders = MainRepository::getCountOrders();
        $paginator = $this->orderRepository->getAllOrders($perpage);

        \MetaTag::setTags(['title' => 'Список заказов']);
        return view('shop.admin.order.index', compact('countOrders', 'paginator'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = $this->orderRepository->getId($id);
        if (empty($item)) {
            abort(404);
        }
        $order = $this->orderRepository->getOneOrder($item->id);
        $order_products = $this->orderRepository->getAllOrderProducts($item->id);
        $sum = 0;
        foreach ($order_products as $product) {
            $sum += $product->qty * $product->price;
        }

        \MetaTag::setTags(['title' => "Заказ № {$item->id}"]);
        return view('shop.admin.order.edit', compact('item', 'order', 'order_products', 'sum'));
    }

    public function change($id)
    {
        $result = $this->orderRepository->changeStatusOrder($id);

        if ($result) {
            return redirect()->route('orders.edit', $id)->with(['success' => 'Успешно сохранено!']);
        } else {
            return back()->withErrors(['msg' => "Не хватает товаров для данного заказа"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $st = $this->orderRepository->changeStatusOnDelete($id);
        if ($st) {
            $result = Order::destroy($id);
            if ($result) {
                return redirect()->route('orders.index')->with(['success' => "Заказ № $id отменен!"]);
            } else back()->withErrors(['msg' => 'Ошибка отмены']);
        } else {
            return back()->withErrors(['msg' => 'Статус не изменился']);
        }
    }

    public function changerestore($id) // Метод восстановления Заказа после отмены
    {
        $result = $this->orderRepository->changeStatusOnRestore($id);

        if ($result) {
            return redirect()->route('orders.edit', $id)->with(['success' => "Заказ № $id успешно восстановлен!"]);
        } else {
            return back()->withErrors(['msg' => 'Ошибка сохранения']);
        }
    }

    public function save(AdminOrderSaveRequest $request, $id)
    {
        $result = $this->orderRepository->saveOrderComment($id);
        if ($result) {
            return redirect()->route('orders.edit', $id)->with(['success' => 'Успешно сохранено!']);
        } else {
            return back()->withErrors(['msg' => 'Ошибка сохранения']);
        }
    }

    public function forcedestroy($id)
    {
        if (empty($id)) {
            return back()->withErrors(['msg' => 'Заказ не найден!']);
        }
        $st = \DB::table('orders')->delete($id);
        if ($st) {
            return redirect()->route('orders.index')->with(['success' => "Заказ № $id успешно удален!"]);
        } else {
            return back()->withErrors(['msg' => 'Ошибка удаления']);
        }
    }
}
