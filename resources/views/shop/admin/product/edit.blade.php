@extends('layouts.app_admin')
@section('content')

    <section class="content-header">
        <h1>
            Редактирование товара: {{ $product->title }}
        </h1>
        @component('shop.admin.components.breadcrumbs')
            @slot('parent') Главная @endslot
            @slot('product') Список товаров @endslot
            @slot('active') Товар № {{ $product->id }} @endslot
        @endcomponent
    </section>

    <section class="content">
        <div class="row">
            <div content="col-md-12">
                <div class="box">
                    <form action="{{ route('products.update', $product->id) }}" method="post" data-toggle="validator">
                        @method('PATCH')
                        @csrf
                        <div class="box-body">
                            <div class="form-group has-feedback">
                                <label for="title">Наименование товара</label>
                                <input type="text" name="title" class="form-control" id="title" required value="{{ old('title', $product->title) }}">
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for="parent_id">Категория</label>
                                <select name="parent_id" class="form-control" id="parent_id">
                                    @include('shop.admin.product.include.category_for_product', ['categories' => $categories])
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="keywords">Ключевые слова</label>
                                <input type="text" name="keywords" class="form-control" id="keywords" value="{{ old('keywords', $product->keywords) }}">
                            </div>
                            <div class="form-group">
                                <label for="description">Описание</label>
                                <input type="text" name="description" class="form-control" id="description" value="{{ old('description', $product->description) }}">
                            </div>
                            <div class="form-group has-feedback">
                                <label for="count">Количество</label>
                                <input type="text" name="count" class="form-control" id="count" pattern="^[ 0-9]+$"
                                       data-error="Допускаются только цифры (целое число)" required value="{{ old('count', $product->count) }}">
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>

                                <div class="help-block with-errors"></div>

                            </div>
                            <div class="form-group has-feedback">
                                <label for="price">Цена</label>
                                <input type="text" name="price" class="form-control" id="price" pattern="^[0-9.]{1,}$"
                                       data-error="Допускаются только цифры и десятичная точка" required value="{{ old('price', $product->price) }}">
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>

                                <div class="help-block with-errors"></div>

                            </div>
                            <div class="form-group has-feedback">
                                <label for="old_price">Прошлая цена</label>
                                <input type="text" name="old_price" class="form-control" id="old_price" pattern="^[0-9.]{1,}$"
                                       data-error="Допускаются только цифры и десятичная точка" value="{{ old('old_price', $product->old_price) }}">
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>

                                <div class="help-block with-errors"></div>

                            </div>
                            <div class="form-group">
                                <label for="editor1">Контент</label>
                                <small> (не рекомендуется делать выравнивание текста по левому или правому краю изображения; не рекомендуется загружать файлы с расширениями: .txt, ...)</small>
                                <textarea name="content" id="editor1" cols="80" rows="10">{{ old('content', $product->content) }}</textarea>
                            </div>
                            <small>Статус изменится автоматически, в зависимости от указанного кол-ва товара
                            (при кол-ве товара равному 0, статус не может быть выбран)</small>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="status" {{ $product->status ? ' checked' : null}}> Статус
                                </label>
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="hit" {{ $product->hit ? ' checked' : null}}> Хит
                                </label>
                            </div>

                            <div class="form-group has-feedback">
                                <label for="related">Связанные товары</label>
                                <select name="related[]" class="select2 form-control" id="related" style="width: 100%" multiple>
                                    @if(!empty($related_products))
                                        @foreach($related_products as $value)
                                            <option value="{{ $value->related_id }}" selected>
                                                {{ $value->title }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Фильтры товара</label>
                                {{ Widget::run('filter', ['tpl' => 'widgets.filter', 'filter' => $filters, 'category' => null,]) }}
                            </div>

                            <div class="form-group">
                                <p style="color: #dd4b39;"><strong>Базовое изображение должно быть одним из изображений галереи</strong></p>
                                <div class="col-md-4">
                                    @include('shop.admin.product.include.image_single_edit')
                                </div>
                                <div class="col-md-8">
                                    @include('shop.admin.product.include.image_gallery_edit')
                                </div>
                            </div>
                        </div>

                        <input type="hidden" id="_token" value="{{ csrf_token() }}">

                        <div class="box-footer">
                            <button type="submit" class="btn btn-warning">Сохранить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <div class="hidden" data-name="{{ $product->id }}"></div>
@endsection