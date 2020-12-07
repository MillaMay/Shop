<!-- INDEX-VALUES-->
@extends('layouts.app_admin')
@section('content')

    <section class="content-header">
        <h1>
            Панель управления
            <a href="{{ url('/admin/filter/values-add-value') }}" class="btn btn-success btn-xs">Добавить фильтр</a>
        </h1>
        @component('shop.admin.components.breadcrumbs')
            @slot('parent') Главная @endslot
            @slot('active') Список фильтров @endslot
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
                                    <th>Группа</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($attrs as $attr)
                                        <tr>
                                            <td>{{ $attr->id }}</td>
                                            <td>{{ $attr->value }}</td>
                                            <td>{{ $attr->title }}</td>
                                            <td>
                                                <a href="{{ url('/admin/filter/value-edit', $attr->id) }}">
                                                    <i class="fa fa-fw fa-eye" title="Редактировать"></i></a>
                                                <a href="{{ url('/admin/filter/value-delete', $attr->id) }}" class="deletebd">
                                                    <i class="fa fa-fw fa-close text-danger" title="Удалить из БД"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination -->
                        <div class="text-center">
                            <p>{{ count($attrs) }} фильтра(ов) из {{ $count }}</p>
                            {{ $attrs }}
                        </div>
                        <!-- ========== -->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection