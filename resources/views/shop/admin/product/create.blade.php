@extends('layouts.app_admin')
@section('content')

    <section class="content-header">
        @component('shop.admin.components.breadcrumbs')
            @slot('title') Новый товар @endslot
            @slot('parent') Главная @endslot
            @slot('product') Список товаров @endslot
            @slot('active') Добавление товара @endslot
        @endcomponent
    </section>

    <section class="content">
        <div class="row">
            <div content="col-md-12">
                <div class="box">
                    <form action="{{ route('products.store') }}" method="post" data-toggle="validator" id="add">
                        @csrf
                        <div class="box-body">
                            <div class="form-group has-feedback">
                                <label for="title">Наименование товара</label>
                                <input type="text" name="title" class="form-control" id="title" placeholder="Введите название товара"
                                       required value="{{ old('title') }}">
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for="parent_id">Категория</label>
                                <select name="parent_id" class="form-control" id="parent_id">
                                    <option>-- выберите категорию --</option>
                                    @include('shop.admin.category.include.edit_categories_all_list', ['categories' => $categories])
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="keywords">Ключевые слова</label>
                                <input type="text" name="keywords" class="form-control" id="keywords" placeholder="Введите ключевые слова"
                                       value="{{ old('keywords') }}">
                            </div>
                            <div class="form-group">
                                <label for="description">Описание</label>
                                <input type="text" name="description" class="form-control" id="description" placeholder="Введите описание"
                                       value="{{ old('description') }}">
                            </div>
                            <div class="form-group has-feedback">
                                <label for="count">Количество</label>
                                <input type="text" name="count" class="form-control" id="count" pattern="^[ 0-9]+$"
                                       data-error="Допускаются только цифры (целое число)" required value="{{ old('count') }}">
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>

                                <div class="help-block with-errors"></div>

                            </div>
                            <div class="form-group has-feedback">
                                <label for="price">Цена</label>
                                <input type="text" name="price" class="form-control" id="price" placeholder="Введите цену" pattern="^[0-9.]{1,}$"
                                       data-error="Допускаются только цифры и десятичная точка" required value="{{ old('price') }}">
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>

                            <div class="help-block with-errors"></div>

                            </div>
                            <div class="form-group has-feedback">
                                <label for="old_price">Прошлая цена</label>
                                <input type="text" name="old_price" class="form-control" id="old_price" placeholder="Введите прошлую цену" pattern="^[0-9.]{1,}$"
                                       data-error="Допускаются только цифры и десятичная точка" value="{{ old('old_price') }}">
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>

                                <div class="help-block with-errors"></div>

                            </div>
                            <div class="form-group">
                                <label for="editor1">Контент</label>
                                <small> (не рекомендуется делать выравнивание текста по левому или правому краю изображения; не рекомендуется загружать файлы с расширениями: .txt, ...)</small>
                                <textarea name="content" id="editor1" cols="80" rows="10">{{ old('content') }}</textarea>
                            </div>
                            <small>Если количество товара равно нулю, то статус не может быть выбран!</small>
                            <div class="form-group">
                            <label>
                                <input type="checkbox" name="status" checked> Статус
                            </label>
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="hit"> Хит
                                </label>
                            </div>

                            <div class="form-group has-feedback">
                                <label for="related">Связанные товары</label>
                                <select name="related[]" class="select2 form-control" id="related" style="width: 100%" multiple>

                                </select>
                            </div>

                            <div class="form-group">
                                <label>Фильтры товара</label>
                                {{ Widget::run('filter', ['tpl' => 'widgets.filter', 'filter' => null, 'category' => null,]) }}
                            </div>

                            <div class="form-group">
                                <div class="col-md-4">
                                    @include('shop.admin.product.include.image_single_create')
                                </div>
                                <div class="col-md-8">
                                    @include('shop.admin.product.include.image_gallery_create')
                                </div>
                            </div>
                        </div>

                        <input type="hidden" id="_token" value="{{ csrf_token() }}">

                        <div class="box-footer">
                            <button type="submit" class="btn btn-success">Добавить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection