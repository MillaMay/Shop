@extends('layouts.app_admin')
@section('content')

    <section class="content-header">
        <h1>
            Редактирование категории: {{ $item->title }}
        </h1>

        @component('shop.admin.components.breadcrumbs')
            @slot('parent') Главная @endslot
            @slot('category') Список категорий @endslot
            @slot('active') Категория id [{{ $item->id }}] @endslot
        @endcomponent
    </section>

    <section class="content">
        <div class="row">
            <div content="col-md-12">
                <div class="box">
                    <form action="{{ route('categories.update', $item->id) }}" method="post" data-toggle="validator">
                        @method('PATCH')
                        @csrf
                        <div class="box-body">
                            <div class="form-group has-feedback">
                                <label for="title">Наименование категории</label>
                                <input type="text" name="title" class="form-control" id="title" placeholder="Введите название категории"
                                       required value="{{ old('title', $item->title) }}">
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <select name="parent_id" id="parent_id" class="form-control" required>
                                    <option value="0">-- самостоятельная категория --</option>
                                    @include('shop.admin.category.include.edit_categories_all_list', ['categories' => $categories])
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="keywords">Ключевые слова</label>
                                <input type="text" name="keywords" class="form-control" id="keywords" placeholder="Введите ключевые слова"
                                       required value="{{ old('keywords', $item->keywords) }}">
                            </div>
                            <div class="form-group">
                                <label for="description">Описание</label>
                                <input type="text" name="description" class="form-control" id="description" placeholder="Введите описание"
                                       required value="{{ old('description', $item->description) }}">
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-warning">Сохранить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection