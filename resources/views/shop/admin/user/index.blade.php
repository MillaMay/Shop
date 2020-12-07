<!-- USERS -->
@extends('layouts.app_admin')
@section('content')

    <section class="content-header">
        <h1>
            Панель управления
            <a href="{{ route('users.create') }}" class="btn btn-success btn-xs">Добавить юзера</a>
        </h1>
        @component('shop.admin.components.breadcrumbs')
            @slot('parent') Главная @endslot
            @slot('active') Список юзеров @endslot
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
                                    <th>Email</th>
                                    <th>Имя</th>
                                    <th>Телефон</th>
                                    <th>Роль</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($paginator as $user)
                                @php
                                    $class = '';
                                    $status = $user->role;
                                    if ($status == 'disabled') $class = "danger";
                                @endphp
                                <tr class="{{ $class }}">
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->email }}</td>
                                    @if($user->role == 'admin')
                                        <td>{{ ucfirst($user->name) }}</td>
                                    @else
                                        <td><a href="{{ route('users', $user->id) }}" title="Личный кабинет">{{ ucfirst($user->name) }}</a></td>
                                    @endif
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>
                                        <a href="{{ route('users.edit', $user->id) }}" title="Посмотреть юзера"><i class="btn btn-xs"></i>
                                            <button type="submit" class="btn btn-warning btn-xs">Редактировать</button>
                                        </a>
                                        <a class="btn btn-xs">
                                            <form method="post" action="{{ route('users.destroy', $user->id) }}"
                                                  style="float: none">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-xs deletebd">Удалить</button>
                                            </form>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <h2>Пользователей нет</h2>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination -->
                        <div class="text-center">
                            <p>{{ count($paginator) }} юзера(ов) из {{ $countUsers }}</p>
                            @if ($paginator->total() > $paginator->count())
                                <div class="row justify-content-center">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                {{ $paginator->links() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection