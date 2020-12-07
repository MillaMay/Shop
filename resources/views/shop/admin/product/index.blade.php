<!-- PRODUCTS -->
@extends('layouts.app_admin')
@section('content')

    <section class="content-header">
        <h1>
            Панель управления
            <a href="{{ route('products.create') }}" class="btn btn-success btn-xs">Добавить товар</a>
        </h1>
        @component('shop.admin.components.breadcrumbs')
            @slot('parent') Главная @endslot
            @slot('active') Список товаров @endslot
        @endcomponent
    </section>

    <section class="content">
    <div class="row">
        <div content="col-md-12">
            <div class="box">
                <div class="box-body">

                    @foreach($getAllProducts as $product)
                        @if($product->count > 0 && $product->status == 0)
                            <div style="text-align: center; background-color: #dff0d8; color: red; padding: 0.5%">
                                Не рекомендуется выключенный статус товара, когда он есть в наличии!
                            </div>
                        @endif
                    @endforeach

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Товар №</th>
                                <th>Наименование</th>
                                <th>Категория</th>
                                <th>Кол-во</th>
                                <th>Прошлая цена</th>
                                <th>Цена</th>
                                <th>Статус</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($getAllProducts as $product)
                                @php $class = !$product->status ? 'success' : '' @endphp

                                    @if($product->status == 0)
                                        <tr class="{{ $class }}" style="font-weight: bold">
                                    @endif
                                    @if($product->count > 0 && $product->status == 0)
                                        <tr style="background-color: #D0E9C6; font-weight: bold">
                                    @endif
                                    <td>{{ $product->id }}</td>
                                    <td>{{ $product->title }}</td>
                                    <td>{{ $product->cat }}</td>
                                    @if($product->count > 0 && $product->status == 0)
                                        <td style="color: red">{{ $product->count }}</td>
                                    @else
                                        <td>{{ $product->count }}</td>
                                    @endif
                                    <td>{{ $product->old_price }}</td>
                                    <td>{{ $product->price }}</td>
                                    <td>{{ $product->status ? 'On' : 'Off' }}</td>
                                    <td>
                                        <a href="{{ route('products.edit', $product->id) }}" title="Редактировать">
                                            <i class="fa fa-fw fa-eye"></i>
                                        </a>
                                        @if($product->status == 0)
                                            <a href="{{ route('products.returnstatus', $product->id) }}" class="delete" title="Сменить status на On">
                                                <i class="fa fa-fw fa-refresh"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('products.deletestatus', $product->id) }}" class="delete" title="Сменить status на Off">
                                                <i class="fa fa-fw fa-close"></i>
                                            </a>
                                        @endif

                                        @if($product)
                                            <a href="{{ route('products.deleteproduct', $product->id) }}" class="deletebd" title="Удалить из БД">
                                                <i class="fa fa-fw fa-close text-danger"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="text-center">
                        <p>{{ count($getAllProducts) }} товара(ов) из {{ $count }}</p>
                        @if ($getAllProducts->total() > $getAllProducts->count())
                            <div class="row justify-content-center">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            {{ $getAllProducts->links() }}
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
@endsection