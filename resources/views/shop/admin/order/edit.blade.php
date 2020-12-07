@extends('layouts.app_admin')
@section('content')

    <section class="content-header">
        <h1>
            Редактирование заказа
            @if(!$order->status)
                <a href="{{ route('orders.change', $item->id) }}/?status=1" class="btn btn-success btn-xs confirm">Подтвердить</a>
                <a href="#" class="btn btn-warning btn-xs redact">Редактировать</a>
            @else
            <a href="{{ route('orders.change', $item->id) }}/?status=0" class="btn btn-default btn-xs">Вернуть на доработку</a>
            @endif
            @if($order->status != '1')
            <a href="" class="btn btn-xs">
                <form id="delform" method="post" action="{{ route('orders.destroy', $item->id) }}" style="float: none">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger btn-xs {{--delete--}}">Отменить</button>
                </form>
            </a>
            @endif
        </h1>

        @component('shop.admin.components.breadcrumbs')
            @slot('parent') Главная @endslot
            @slot('order') Список заказов @endslot
            @slot('active') Заказ № {{ $item->id }} @endslot
        @endcomponent
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="row">
            <div content="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <form action="{{ route('orders.save', $item->id) }}" method="post">
                                @csrf
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                    <tr>
                                        <td>Номер заказа</td>
                                        <td>{{ $order->id }}</td>
                                    </tr>
                                    <tr>
                                        <td>Дата заказа</td>
                                        <td>{{ $order->created_at }}</td>
                                    </tr>
                                    <tr>
                                        <td>Дата изменения</td>
                                        <td>{{ $order->updated_at }}</td>
                                    </tr>
                                    <tr>
                                        <td>Кол-во позиций в заказе</td>
                                        <td>{{ count($order_products) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Сумма</td>
                                        <td>{{ $sum }} {{ $order->currency }}</td>
                                    </tr>
                                    <tr>
                                        <td>Имя заказчика</td>
                                        <td><a href="{{ route('users.edit', $order->user_id) }}">{{ ucfirst($order->name) }}</a></td>
                                    </tr>
                                    <tr>
                                        <td>Статус</td>
                                        <td>{{ $order->status ? 'Завершен' : 'Новый' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Комментарий</td>
                                        <td>
                                            <input type="text" value="@if (isset($order->note)) {{ $order->note }} @endif" placeholder="@if (!isset($order->note)) комментариев нет @endif" name="comment">
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <input type="submit" name="submit" class="btn btn-warning" value="Сохранить">
                            </form>
                        </div>
                    </div>
                </div>

                <h3 style="margin-left: 15px">Детали заказа</h3>
                <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>ID позиции</th>
                                    <th>Наименование</th>
                                    <th>Общее кол-во</th>
                                    <th style="color: #d73925;">Кол-во в наличии</th>
                                    <th>Сумма</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $qty = 0 @endphp
                                @foreach($order_products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td><a href="{{ route('products.edit', $product->prod_id) }}">{{ $product->title }}</a></td>
                                        <td>{{ $product->qty, $qty += $product->qty }}</td>
                                        <td style="color: #d73925;">{{ $product->count }}</td>
                                        <td>{{ $product->qty * $product->price }}</td>
                                    </tr>
                                @endforeach
                                <tr class="active">
                                    <td colspan="2"><b>Итого:</b></td>
                                    <td colspan="2"><b>{{ $qty }}</b></td>
                                    <td><b>{{ $sum }} {{ $order->currency }}</b></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection