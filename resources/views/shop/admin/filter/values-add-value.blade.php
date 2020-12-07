@extends('layouts.app_admin')
@section('content')

    <section class="content-header">
        @component('shop.admin.components.breadcrumbs')
            @slot('title') Новый фильтр @endslot
            @slot('parent') Главная @endslot
            @slot('values_filter') Список фильтров @endslot
            @slot('active') Добавление фильтра @endslot
        @endcomponent
    </section>

    <section class="content">
        <div class="row">
            <div content="col-md-12">
                <div class="box">
                    <form action="{{ url('/admin/filter/values-add-value') }}" method="post" data-toggle="validator" id="addattrs">
                        @csrf
                        <div class="box-body">
                            <div class="form-group has-feedback">
                                <label for="value">Наименование фильтра</label>
                                <input type="text" name="value" class="form-control" id="value" placeholder="Введите название фильтра"
                                       required value="{{ old('value') }}">
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div>
                            <div class="form-group">
                                <label for="group_id">Группа</label>
                                <select name="attr_group_id" class="form-control" id="group_id">
                                    <option>Выберите группу</option>
                                    @foreach($group as $item)
                                        <option value="{{ $item->id }}">{{ $item->title }}</option>
                                    @endforeach
                                </select>
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