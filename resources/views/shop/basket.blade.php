@extends('layouts.app_shop')

@section('content')
    <div class="ckeckout">
        <div class="container">
            <div class="ckeck-top heading">
                <h2>CHECKOUT</h2>
            </div>
            <div class="ckeckout-top">
                <div class="cart-items">

                    <script>$(document).ready(function(c) {
                            $('.close1').on('click', function(c){
                                $('.cart-header').fadeOut('slow', function(c){
                                    $('.cart-header').remove();
                                });
                            });
                        });
                    </script>

                    <div class="in-check">
                        <ul class="unit">
                            <li><span>Item</span></li>
                            <li style=""><span>Product Name</span></li>
                            <li><span>Unit Price</span></li>
                            <li><span>Total Price</span></li>
                            <li> </li>
                            <li> </li>
                            <div class="clearfix"> </div>
                        </ul>

                        @php $qty = 0 @endphp
                        @foreach($order->products as $product)
                        <ul class="cart-header">
                            <form action="{{ route('basket-remove', $product) }}" method="POST">
                                {{--$product передан без id, потому что по умолчанию и так передается нужный id--}}
                                <button type="submit" class="item_add" style="background: none">
                                    <div class="close1" title="Remove From Order"> </div>
                                </button>
                                @csrf
                            </form>

                            <li class="ring-in"><a href="{{ route('product', $product->id) }}" >
                                    @if(empty($product->img))
                                        <img src="{{ asset('/images/no_image.png') }}" class="img-responsive" alt="">
                                    @else
                                        <img src="{{ asset("/uploads/single/$product->img") }}" class="img-responsive" alt="">
                                    @endif
                                </a></li>
                            <li><span class="name">{{ $product->title }}</span></li>
                            <li><span class="cost">{{ number_format($product->price * $currency->value, null, null, " ") }} {{ App\Services\CurrencyConversion::getCurrencyCode() }}</span></li>
                            <li><span class="cost">{{ number_format($product->getPriceForCount(), null, null, " ") }} {{ App\Services\CurrencyConversion::getCurrencyCode() }}</span></li>
                            <div class="clearfix"> </div>
                            <div style="float: right; margin-right: -2.3%; margin-bottom: -1%">
                                <form action="{{ route('basket-add', $product) }}" method="POST">
                                    {{--$product передан без id, потому что по умолчанию и так передается нужный id--}}
                                    <span class="cost">({{ $product->pivot->qty, $qty += $product->pivot->qty }})</span>
                                    <button type="submit" class="btn" style="background: none; outline: none">
                                        <img src="{{ asset('/images/plusss.jpg') }}" title="Add More">
                                    </button>
                                    @csrf
                                </form>
                            </div>
                            <div class="clearfix"> </div>
                        </ul>
                        @endforeach

                    </div>
                </div>
            </div>
            <div>
                <h4 class="text-right" style="margin-right: 1.5%"><span class="cost" style="margin-right: 1%">({{ $qty }})</span>
                    <strong style="color: #696969"> In Total: <span style="color: #494f54; font-size: 22px">{{ number_format($order->getFullPrice(), 2, null, " ") }}</span>
                        {{ App\Services\CurrencyConversion::getCurrencyCode() }}</strong></h4>
            </div>
            <form action="{{ route('basket-confirm') }}" method="POST">
                <div class="text-center mask" style="margin-right: 1.5%">
                    <div class="address">
                        <input type="submit" value="Confirm Order">
                    </div>
                </div>
            @csrf
            </form>
        </div>
    </div>
@endsection