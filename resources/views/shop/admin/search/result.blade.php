@extends('layouts.app_admin')
@section('content')

    <section class="content-header">
        @component('shop.admin.components.breadcrumbs')
            @slot('title') Результат запроса: "{{ $query }}" @endslot
            @slot('parent') Главная @endslot
            @slot('active') Поиск @endslot
        @endcomponent
    </section>

    <div class="prdt">
        <div class="container">
            <div class="prdt-top">
                <div class="col-md-9 prdt-left">
                    @if(!empty($products))
                        <div class="product-one">
                            @foreach($products as $product)
                                @php

                                @endphp
                                <div class="col-md-4 product-left p-left" style="min-height: 470px">{{-- установлена высота для выравнивания блока--}}
                                    <div class="product-main simpleCart_shelfItem">
                                        <a href="#" class="mask">
                                            @if(empty($product->img))
                                                <img class="img-responsive zoom-img"
                                                     src="{{ asset('/images/no_image.png') }}">
                                            @else
                                                <img class="img-responsive zoom-img"
                                                     src="{{ asset("/uploads/single/$product->img") }}">
                                            @endif
                                        </a>
                                        <div class="product-bottom">
                                            <a href="{{ route('products.edit', $product->id) }}" class="mask"><h3>{{ $product->title }}</h3></a>
                                            <p>Explore Now</p>
                                            <h4>
                                                <a href="#" data-id="{{ $product->id }}" class="add-to-cart-link">
                                                    <i></i></a>
                                                <span class="item-price">
                                                    {{ $currency->symbol_left }} {{ $product->price * $currency->value }}
                                                    {{ $currency->code }}
                                                </span>
                                                @if($product->old_price)
                                                    <small><del>{{ $currency->symbol_left }}
                                                            {{ $product->old_price * $currency->value }}
                                                        {{ $currency->code }}</del></small>
                                                @endif
                                            </h4>
                                        </div>
                                        <div class="srch srch1">
                                            <span>{{ $product->description }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="clearfix"></div>
                        </div>
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
@endsection