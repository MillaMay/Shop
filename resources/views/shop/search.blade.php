@extends('layouts.app_shop')

@section('content')

    <div class="product">
        <div class="container">
            <div class="product-top">
                <div class="col-md-12 prdt-left">
                    <div class="product-one">
                        @include('shop.include.cardProduct')
                        <div class="clearfix"></div>
                    </div>
                    <div class="text-center" style="margin-top: 3%">{{ $products->withQueryString()->links() }}</div>
                </div>
                {{--{{ Widget::run('filter', ['tpl' => 'widgets.filtersShop', 'filter' => null, 'category' => null]) }}--}}
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

@endsection