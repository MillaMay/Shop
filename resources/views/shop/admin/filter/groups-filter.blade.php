<!-- INDEX-GROUPS-->
@extends('layouts.app_admin')
@section('content')

    <section class="content-header">
        <h1>
            Панель управления
            <a href="{{ url('/admin/filter/groups-add-group') }}" class="btn btn-success btn-xs">Добавить группу</a>
        </h1>
        @component('shop.admin.components.breadcrumbs')
            @slot('parent') Главная @endslot
            @slot('active') Список групп фильтров @endslot
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
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($attr_groups as $attr)
                                        <tr>
                                            <td>{{ $attr->id }}</td>
                                            <td>{{ $attr->title }}</td>
                                            <td>
                                                <a href="{{ url('/admin/filter/group-edit', $attr->id) }}">
                                                    <i class="fa fa-fw fa-eye" title="Редактировать"></i></a>
                                                <a href="{{ url('/admin/filter/group-delete', $attr->id) }}" class="deletebd">
                                                    <i class="fa fa-fw fa-close text-danger" title="Удалить из БД"></i></a>
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