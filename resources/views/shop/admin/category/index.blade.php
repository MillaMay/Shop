<!-- CATEGORIES -->
@extends('layouts.app_admin')
@section('content')

    <section class="content-header">
        <h1>
            Панель управления
            <a href="{{ route('categories.create') }}" class="btn btn-success btn-xs">Добавить категорию</a>
        </h1>
        @component('shop.admin.components.breadcrumbs')
            @slot('parent') Главная @endslot
            @slot('active') Список категорий @endslot
        @endcomponent
    </section>

    <section class="content">
        <div class="row">
            <div content="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div width="100%">
                            <small style="margin-left: 70px">Для редактирования категории - нажмите на эту категорию</small>
                        </div>
                        <br>
                        @if($menu)
                            <div class="list-group list-group-root well">
                                @include('shop.admin.category.menu.customMenuItems', ['items' => $menu->roots()])
                                {{--@php dd($menu->roots()); @endphp--}}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection