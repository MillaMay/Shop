@extends('layouts.app_admin')
@section('content')

    <section class="content-header">
        @component('shop.admin.components.breadcrumbs')
            @slot('title') Новая валюта @endslot
            @slot('parent') Главная @endslot
            @slot('currency') Список валют @endslot
            @slot('active') Добавление валюты @endslot
        @endcomponent
    </section>

    <section class="content">
        <div class="row">
            <div content="col-md-12">
                <div class="box">
                    <form action="{{ url('/admin/currency/add') }}" method="post" data-toggle="validator">
                        @csrf
                        <div class="box-body">
                            <div class="form-group has-feedback">
                                <label for="title">Наименование валюты</label>
                                <input type="text" name="title" class="form-control" id="title" placeholder="Введите название валюты"
                                       required value="{{ old('title') }}">
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for="code">Код</label>
                                <input type="text" name="code" class="form-control" id="code" placeholder="Введите трехзначный код, например, UAH"
                                       required value="{{ old('code') }}">
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div>
                            <div class="form-group">
                                <label for="symbol_left">Символ слева</label>
                                <input type="text" name="symbol_left" class="form-control" id="symbol_left" placeholder="Например, ₴"
                                       value="{{ old('symbol_left') }}">
                            </div>
                            <div class="form-group">
                                <label for="symbol_right">Символ справа</label>
                                <input type="text" name="symbol_right" class="form-control" id="symbol_right" placeholder="Например, грн."
                                       value="{{ old('symbol_right') }}">
                            </div>
                            <div class="form-group has-feedback">
                                <label for="value">Значение &nbsp;
                                    <small>(если это базовая валюта (поставьте 1), то курс к базовой валюте)</small>
                                </label>
                                <input type="text" name="value" class="form-control" id="value" placeholder="Введите цифровое значение" required
                                       value="{{ old('value') }}" data-error="Допускаются цифра и десятичная точка" pattern="^[0-9.]{1,}">
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <label><input type="checkbox" name="base"> Базовая валюта</label>
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