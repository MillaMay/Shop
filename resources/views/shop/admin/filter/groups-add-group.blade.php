@extends('layouts.app_admin')
@section('content')

    <section class="content-header">
        @component('shop.admin.components.breadcrumbs')
            @slot('title') Новая группа @endslot
            @slot('parent') Главная @endslot
            @slot('groups_filter') Список групп фильтров @endslot
            @slot('active') Добавление группы фильтров @endslot
        @endcomponent
    </section>

    <section class="content">
        <div class="row">
            <div content="col-md-12">
                <div class="box">
                    <form action="{{ url('/admin/filter/groups-add-group') }}" method="post" data-toggle="validator">
                        @csrf
                        <div class="box-body">
                            <div class="form-group has-feedback">
                                <label for="title">Наименование группы</label>
                                <input type="text" name="title" class="form-control" id="title" placeholder="Введите название группы"
                                       required value="{{ old('title') }}">
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-success">Добавить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection