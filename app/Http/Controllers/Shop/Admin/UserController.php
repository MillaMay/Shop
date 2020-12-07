<?php

namespace App\Http\Controllers\Shop\Admin;

//use Illuminate\Http\Request;
use App\Http\Requests\AdminUserEditRequest;
use App\Models\UserRole;
use App\Models\Admin\User;
use App\Repositories\Admin\MainRepository;
use App\Repositories\Admin\OrderRepository;
use App\Repositories\Admin\UserRepository;
use MetaTag;

// User $user -> namespace App\Models\Admin;

class UserController extends AdminBaseController
{
    private $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = app(UserRepository::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perpage = 10;
        $countUsers = MainRepository::getCountUsers();
        $paginator = $this->userRepository->getAllUsers($perpage);

        MetaTag::setTags(['title' => 'Список юзеров']);
        return view('shop.admin.user.index', compact('countUsers', 'paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        MetaTag::setTags(['title' => 'Добавление юзера']);
        return view('shop.admin.user.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdminUserEditRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminUserEditRequest $request)
    {
        $user = User::create([
           'name' => $request['name'],
           'phone' => $request['phone'],
           'email' => $request['email'],
           'password' => bcrypt($request['password']),
        ]);

        if (!$user) {
            return back()->withErrors(['msg' => 'Ошибка добавления'])->withInput();
        } else {
            $role = UserRole::create([
               'user_id' => $user->id,
               'role_id' => (int)$request['role'],
            ]);
        }
        if (!$role) {
            return back()->withErrors(['msg' => 'Ошибка добавления'])->withInput();
        } else {
            return redirect()->route('users.index')
                ->with(['success' => 'Успешно добавлено!']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $perpage = 7;
        $item = $this->userRepository->getId($id);
        if (empty($item)) {
            abort(404);
        }
        $orders = $this->userRepository->getUserOrders($id, $perpage);
        foreach ($orders as $order) {
            $order_id = $order->id;
        }
        if (isset($order_id)) {
            // Для получения полной суммы заказа и кол-ва позиций
            $orderRepository = app(OrderRepository::class);
            $order_products = $orderRepository->getAllOrderProducts($order_id);
            //dd(count($order_products));
            $sum = 0;
            foreach ($order_products as $product) {
                $sum += $product->qty * $product->price;
            }
        } else { // Если у юзера нет заказов
            $order_id = null;
            $order_products = null;
            $sum = null;
        }
        $role = $this->userRepository->getUserRole($id);
        $count = $this->userRepository->getCountOrdersPage($id);
        $count_orders = $this->userRepository->getCountOrders($id, $perpage);

        MetaTag::setTags(['title' => "Юзер id [$item->id]"]);
        return view('shop.admin.user.edit', compact('item','orders', 'role', 'count', 'count_orders', 'order_products', 'sum'));
    }

    /**
     * @param AdminUserEditRequest $request
     * @param User $user
     * @param UserRole $role
     * @return $this
     */
    public function update(AdminUserEditRequest $request, User $user, UserRole $role)
    {
        $user->name = $request['name'];
        $user->phone = $request['phone'];
        $user->email = $request['email'];
        $request['password'] == null ?: $user->password = bcrypt($request['password']);
        $save = $user->save();
        if (!$save) {
            return back()->withErrors(['msg' => 'Ошибка сохранения'])->withInput();
        } else {
            $role->where('user_id', $user->id)->update([
               'role_id' => (int)$request['role'],
            ]);
            return redirect()->route('users.edit', $user->id)
                ->with(['success' => 'Успешно сохранено!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $result = $user->forceDelete();
        if ($result) {
            return redirect()->route('users.index')
                ->with(['success' => "Юзер " . ucfirst($user->name) . " успешно удален!"]);
        } else {
            return back()->withErrors(['msg' => 'Ошибка удаления']);
        }
    }
}
