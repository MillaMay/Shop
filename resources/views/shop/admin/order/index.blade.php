<!-- ORDERS -->
@extends('layouts.app_admin')
@section('content')

    <section class="content-header">
        @component('shop.admin.components.breadcrumbs')
            @slot('title') Панель управления @endslot
            @slot('parent') Главная @endslot
            @slot('active') Список заказов @endslot
        @endcomponent
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="row">
            <div content="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Заказ №</th>
                                    <th>Покупатель</th>
                                    <th>Телефон</th>
                                    <th>Статус</th>
                                    <th>Дата заказа</th>
                                    <th>Дата изменения</th>
                                    <th>Комментарий</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($paginator as $order)
                                    @php $class = $order->status ? 'success' : '' @endphp

                                <tr class="{{ $class }}">
                                    <td>{{ $order->id }}</td>
                                    <td>{{ ucfirst($order->name) }}</td>
                                    <td>{{ $order->phone }}</td>
                                    <td>
                                        @if ($order->status == 0)Новый @endif
                                        @if ($order->status == 1)<b style="font-weight: bold">Завершен</b> @endif
                                        @if ($order->status == 2)<b style="color: red">Отменен</b> @endif
                                    </td>
                                    <td>{{ $order->created_at }}</td>
                                    <td>{{ $order->updated_at }}</td>
                                    <td>@if ($order->note !== 'Note') {{ $order->note }} @endif</td>

                                    <td>
                                        @if ($order->status == 2)
                                            <a href="{{ route('orders.changerestore', $order->id) }}" title="Восстановить"><i class="fa fa-fw fa-exclamation"></i></a>
                                        @else
                                            <a href="{{ route('orders.edit', $order->id) }}" title="ПОДРОБНЕЕ"><i class="fa fa-fw fa-eye"></i></a>
                                        @endif
                                        <a href="{{ route('orders.forcedestroy', $order->id) }}" title="Удалить из БД"><i class="fa fa-fw fa-close text-danger deletebd"></i></a>
                                    </td>
                                </tr>

                                @empty
                                    <tr>
                                        <td class="text-center" colspan="8">
                                            <h2>Заказов нет</h2>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination -->
                        <div class="text-center">
                            <p>Новых заказов {{ $countOrders }}</p>
                            @if ($paginator->total() > $paginator->count())
                                <div class="row justify-content-center">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                {{ $paginator->links() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <!-- ========== -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection