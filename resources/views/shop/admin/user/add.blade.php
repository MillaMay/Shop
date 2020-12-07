@extends('layouts.app_admin')
@section('content')

    <section class="content-header">
        @component('shop.admin.components.breadcrumbs')
            @slot('title') Новый юзер @endslot
            @slot('parent') Главная @endslot
            @slot('user') Список юзеров @endslot
            @slot('active') Добавление юзера @endslot
        @endcomponent
    </section>

    <section class="content">
        <div class="row">
            <div content="col-md-12">
                <div class="box">
                    <form action="{{ route('users.store') }}" method="post" data-toggle="validator">
                        @csrf
                        <div class="box-body">
                            <div class="form-group has-feedback">
                                <label for="name">Имя</label>
                                <input type="text" name="name" class="form-control" id="name" required value="{{ old('name') }}">
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div>
                            <label for="phone">Телефон</label>
                            <div class="form-group row">
                                <div class="col-md-4">
                                <select id="country" class="form-control">
                                    <option value="ru">Россия +7</option>
                                    <option value="ua">Украина +38</option>
                                </select>
                                </div>
                                <div class="col-md-8">
                                <input type="text" name="phone" class="form-control" id="phone">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password">Пароль</label>
                                <input type="text" name="password" class="form-control" id="password" required>
                            </div>
                            <div class="form-group">
                                <label for="password_repeat">Подтверждение пароля</label>
                                <input type="text" name="password_confirmation" class="form-control" id="password_repeat" required>
                            </div>
                            <div class="form-group has-feedback">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control" id="email" required value="{{ old('email') }}">
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for="role">Роль</label>
                                <select name="role" id="role" class="form-control">
                                    <option value="2" selected>Юзер</option>
                                    <option value="3">Админ</option>
                                    <option value="1">Disabled</option>
                                </select>
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" name="id" value="">
                            <button type="submit" class="btn btn-success">Добавить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection