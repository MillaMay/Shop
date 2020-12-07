<!-- CURRENCY -->
@extends('layouts.app_admin')
@section('content')

    <section class="content-header">
        <h1>
            Панель управления
            <a href="{{ url('/admin/currency/add') }}" class="btn btn-success btn-xs">Добавить валюту</a>
        </h1>
        @component('shop.admin.components.breadcrumbs')
            @slot('parent') Главная @endslot
            @slot('active') Список валют @endslot
        @endcomponent
    </section>

    <section class="content">
        <div class="row">
            <div content="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Наименование</th>
                                    <th>Код</th>
                                    <th>Значение</th>
                                    <th>Базовая</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($currency as $item)
                                    <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->code }}</td>
                                    <td>{{ $item->value }}</td>
                                    <td>@if($item->base == 1) Да @else Нет @endif</td>
                                    <td>
                                        <a href="{{ url('/admin/currency/edit', $item->id) }}" title="Редактировать">
                                            <i class="fa fa-fw fa-eye"></i>
                                        </a>
                                        <a href="{{ url('/admin/currency/delete', $item->id) }}" class="deletebd" title="Удалить из БД">
                                            <i class="fa fa-fw fa-close text-danger"></i>
                                        </a>
                                    </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection