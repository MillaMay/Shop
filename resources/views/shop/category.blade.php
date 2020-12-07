@extends('layouts.app_shop')

@section('content')

    <div class="text-center">
    <h3>{{--{{ $category->description }}--}} there must be a description of the category</h3>
    </div>
    <div class="product">
        <div class="container">
            <div class="product-top">
                <div class="col-md-9 prdt-left">
                    <div class="product-one">
                        @if($products->count() > 0)
                            @include('shop.include.cardProduct')
                        @else
                            <h4 style="color: #8996A8">There are no products in this category yet ...</h4>
                        @endif
                        <div class="clearfix"></div>
                    </div>
                    <div class="text-center" style="margin-top: 3%">{{ $products->withQueryString()->links() }}</div>
                </div>
                {{ Widget::run('filter', ['tpl' => 'widgets.filtersShop', 'filter' => null, 'category' => $category,]) }}
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

@endsection