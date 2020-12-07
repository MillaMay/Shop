@extends('layouts.app_admin')
@section('content')

    <section class="content-header">
        <h1>
            Редактирование юзера: {{ ucfirst($item->name) }}
        </h1>
        @component('shop.admin.components.breadcrumbs')
            @slot('parent') Главная @endslot
            @slot('user') Список юзеров @endslot
            @slot('active') Юзер id [{{ $item->id }}] @endslot
        @endcomponent
    </section>

    <section class="content">
        <div class="row">
            <div content="col-md-12">
                <div class="box">
                    <form action="{{ route('users.update', $item->id) }}" method="post" data-toggle="validator">
                        @method('PUT')
                        @csrf
                        <div class="box-body">
                            <div class="form-group has-feedback">
                                <label for="name">Имя</label>
                                <input type="text" name="name" class="form-control" id="name" value="
                                    @if(old('name')){{old('name')}}@else{{ucfirst($item->name) ?? ""}}@endif" required>
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div>
                            <label for="phone">Телефон</label>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <select id="country" class="form-control">
                                        @if(substr($item->phone, 0, 2) == '+7')
                                            <option value="ru">Россия +7</option>
                                            <option value="ua">Украина +38</option>
                                        @else
                                            <option value="ua">Украина +38</option>
                                            <option value="ru">Россия +7</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="phone" class="form-control" id="phone" value="{{ old('phone', $item->phone) }}">
                                </div>
                            </div>
                            <div class="form-group has-feedback">
                                <label for="password">Пароль</label>
                                <input type="text" name="password" class="form-control" id="password"  placeholder="Введите пароль, если хотите его изменить">
                            </div>
                            <div class="form-group has-feedback">
                                <label for="password_repeat">Подтверждение пароля</label>
                                <input type="text" name="password_confirmation" class="form-control" id="password_repeat" placeholder="Подтвердите пароль">
                            </div>
                            <div class="form-group has-feedback">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control" id="email" value="
                                    @if(old('email')){{old('email')}} @else {{$item->email ?? ""}} @endif" required>
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for="role">Роль</label>
                                <select name="role" id="role" class="form-control">
                                    <option value="2" @php if ($role->name == 'user') echo ' selected' @endphp>Юзер</option>
                                    <option value="3" @php if ($role->name == 'admin') echo ' selected' @endphp>Админ</option>
                                    <option value="1" @php if ($role->name == 'disabled') echo ' selected' @endphp>Disabled</option>
                                </select>
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <button type="submit" class="btn btn-warning">Сохранить</button>
                        </div>
                    </form>
                </div>
                <h3 style="margin-left: 15px">Заказы юзера</h3>
                <div class="box">
                    <div class="box-body">
                        @if($count)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>№ заказа</th>
                                        <th>Статус</th>
                                        <th>Сумма</th>
                                        <th>Дата (последняя)</th>
                                        <th>Действия</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $order)
                                        @php $class = $order->status ? 'success' : '' @endphp
                                            <tr class="{{ $class }}">
                                                <td>{{ $order->id }}</td>
                                                @if($order->status == '2')
                                                    <td><b style="color: red">Отменен</b></td>
                                                @else
                                                    <td>{{ $order->status ? 'Завершен' : 'Новый' }}</td>
                                                @endif
                                                <td>{{ $sum }} {{ $order->currency }}</td>
                                                <td>{{ $order->updated_at ?? $order->created_at }}</td>
                                                @if($order->status != '2')
                                                    <td>
                                                        <a href="{{ route('orders.edit', $order->id) }}" title="ПОДРОБНЕЕ">
                                                            <i class="fa fa-fw fa-eye"></i>
                                                        </a>
                                                    </td>
                                                @else
                                                    <td>
                                                        <a href="{{ route('orders.changerestore', $order->id) }}" title="Восстановить">
                                                            <i class="fa fa-fw fa-exclamation"></i>
                                                        </a>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-danger">У этого юзера пока нет заказов.</p>
                        @endif
                    </div>
                </div>
                <!-- Pagination -->
                <div class="text-center">
                    <p>{{ count($count_orders) }} заказа(ов) из {{ $count }}</p>
                    @if ($orders->total() > $orders->count())
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        {{ $orders->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <!-- ========== -->
            </div>
        </div>
    </section>
@endsection