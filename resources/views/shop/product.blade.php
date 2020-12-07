@extends('layouts.app_shop')

@section('content')

    <div class="single contact">
        <div class="container">
            <div class="single-main">
                <div class="col-md-12 single-main-left">
                    <div class="sngl-top">
                        <div class="col-md-4 single-top-left">
                            <div class="flexslider" style="width: 92%"> {{--изменила ширину, потому что убрала правый блок с фильтрами--}}
                                <ul class="slides">

                                    @if(!$gallery)
                                        <li data-thumb="{{ asset('/images/no_image.png') }}">
                                            <div class="thumb-image"> <img src="{{ asset('/images/no_image.png') }}" data-imagezoom="true" class="img-responsive" alt=""/> </div>
                                        </li>
                                    @else
                                        @foreach($gallery as $img)
                                            <li data-thumb="{{ asset("/uploads/gallery/$img") }}">
                                                <div class="thumb-image"> <img src="{{ asset("/uploads/gallery/$img") }}" data-imagezoom="true" class="img-responsive" alt=""/> </div>
                                            </li>
                                        @endforeach
                                    @endif

                                </ul>
                            </div>
                            <!-- FlexSlider -->
                            <script src="{{ asset('js/imagezoom.js') }}"></script>
                            <script defer src="{{ asset('js/jquery.flexslider.js') }}"></script>
                            <link rel="stylesheet" href="{{ asset('css/flexslider.css') }}" type="text/css" media="screen" />

                            <script>
                                // Can also be used with $(document).ready()
                                $(window).load(function() {
                                    $('.flexslider').flexslider({
                                        animation: "slide",
                                        controlNav: "thumbnails"
                                    });
                                });
                            </script>

                        </div>
                        <div class="col-md-8 single-top-right">
                            <div class="single-para simpleCart_shelfItem">
                                <h2>{{ $prod->title }}</h2>
                                {{--<div class="star-on">--}}
                                    {{--<ul class="star-footer"> --}}{{--оценка покупателя--}}
                                        {{--<li><a href="#"><i> </i></a></li>--}}
                                        {{--<li><a href="#"><i> </i></a></li>--}}
                                        {{--<li><a href="#"><i> </i></a></li>--}}
                                        {{--<li><a href="#"><i> </i></a></li>--}}
                                        {{--<li><a href="#"><i> </i></a></li>--}}
                                    {{--</ul>--}}
                                    {{--<div class="review">--}}
                                        {{--<a href="#"> 1 customer reviews </a>--}}
                                    {{--</div>--}}
                                    {{--<div class="clearfix"> </div>--}}
                                {{--</div>--}}
                                <h5 class="item_price">
                                    {{ number_format($prod->price * $currency->value, null, null, " ") }} {{ App\Services\CurrencyConversion::getCurrencyCode() }}
                                    @if($prod->old_price > $prod->price)
                                        <small>
                                            <del>{{ number_format($prod->old_price * $currency->value, null, null, " ") }} {{ App\Services\CurrencyConversion::getCurrencyCode() }}</del>
                                        </small>
                                    @endif
                                </h5>
                                <p>{{ $prod->description }}</p>
                                <div class="available">
                                    <ul>
                                        <li>Color
                                            <select>
                                                <option>Silver</option>
                                                <option>Black</option>
                                                <option>Dark Black</option>
                                                <option>Red</option>
                                            </select>
                                        </li>
                                        <div class="clearfix"> </div>
                                    </ul>
                                </div>
                                <ul class="tag-men"> {{--Пока 5 ступеней вложенности для показа--}}
                                    <li><span>TAG</span>
                                        <span class="women1">: {{ $category->title }}</span></li>
                                    @if(!empty($parent2_category))
                                        <li><span>From TAG</span>
                                            <span class="women1">: {{ $parent2_category->title }}</span></li>
                                    @endif
                                    @if(!empty($parent3_category))
                                        <li><span  style="margin-right: 5%">From TAG</span>
                                            <span class="women1">: {{ $parent3_category->title }}</span></li>
                                    @endif
                                    @if(!empty($parent4_category))
                                        <li><span  style="margin-right: 10%">From TAG</span>
                                            <span class="women1">: {{ $parent4_category->title }}</span></li>
                                    @endif
                                    @if(!empty($parent5_category))
                                        <li><span  style="margin-right: 15%">From TAG</span>
                                            <span class="women1">: {{ $parent5_category->title }}</span></li>
                                    @endif
                                    <li><span>SKU</span>
                                        <span class="women1">: {{ $prod->id }}</span></li>
                                </ul>
                                @if($prod->status == 1)
                                    <form action="{{ route('basket-add', $prod->id) }}" method="POST">
                                        <button type="submit" class="add-cart item_add">ADD TO CART</button>
                                        @csrf
                                    </form>
                                @else
                                    <h3>Not available yet...</h3>

                                    <div class="col-md-7 footer-left" style="padding-left: 0">
                                        <form action="{{ route('subscription', $prod) }}" method="POST">
                                            <input type="text" name="email" value="Enter Your Email" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Enter Your Email';}">
                                            <input type="submit" value="Subscribe">
                                            @csrf
                                        </form>
                                    </div>

                                @endif
                            </div>
                        </div>
                        <div class="clearfix"> </div>
                    </div>

                    <script>
                        // My script
                        $(function() {
                            $('.item1').click(function() {
                                $('.contents1').slideToggle();
                            });
                            $('.item2').click(function() {
                                $('.contents2').slideToggle();
                            });
                            $('.item3').click(function() {
                                $('.contents3').slideToggle();
                            });
                            $('.item4').click(function() {
                                $('.contents4').slideToggle();
                            });
                            $('.item5').click(function() {
                                $('.contents5').slideToggle();
                            });
                        });
                    </script>

                    <div class="tabs">
                        <ul class="menu_drop">
                            {{--<li class="item1"><a><img src="{{ asset('images/arrow.png') }}" alt="">Comments (10)</a>--}}
                                {{--<ul style="display: none" class="contents1">--}}
                                    {{--<li class="subitem1"><a href="#">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</a></li>--}}
                                    {{--<li class="subitem2"><a href="#"> Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore</a></li>--}}
                                    {{--<li class="subitem3"><a href="#">Mirum est notare quam littera gothica, quam nunc putamus parum claram, anteposuerit litterarum formas humanitatis per seacula quarta decima et quinta decima. Eodem modo typi, qui nunc nobis videntur parum clari, fiant sollemnes </a></li>--}}
                                {{--</ul>--}}
                            {{--</li>--}}
                            {{--<li class="item2"><a><img src="{{ asset('images/arrow.png') }}" alt="">Additional information</a>--}}
                                {{--<ul style="display: none" class="contents2">--}}
                                    {{--<li class="subitem2"><a href="#"> Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore</a></li>--}}
                                    {{--<li class="subitem3"><a href="#">Mirum est notare quam littera gothica, quam nunc putamus parum claram, anteposuerit litterarum formas humanitatis per seacula quarta decima et quinta decima. Eodem modo typi, qui nunc nobis videntur parum clari, fiant sollemnes </a></li>--}}
                                {{--</ul>--}}
                            {{--</li>--}}
                            {{--<li class="item3"><a><img src="{{ asset('images/arrow.png') }}" alt="">Helpful Links</a>--}}
                                {{--<ul style="display: none" class="contents3">--}}
                                    {{--<li class="subitem1"><a href="#">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</a></li>--}}
                                    {{--<li class="subitem2"><a href="#"> Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore</a></li>--}}
                                    {{--<li class="subitem3"><a href="#">Mirum est notare quam littera gothica, quam nunc putamus parum claram, anteposuerit litterarum formas humanitatis per seacula quarta decima et quinta decima. Eodem modo typi, qui nunc nobis videntur parum clari, fiant sollemnes </a></li>--}}
                                {{--</ul>--}}
                            {{--</li>--}}
                            {{--<li class="item4"><a><img src="{{ asset('images/arrow.png') }}" alt="">Question-Answer (1)</a>--}}
                                {{--<ul style="display: none" class="contents4">--}}
                                    {{--<li class="subitem2"><a href="#"> Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore</a></li>--}}
                                    {{--<li class="subitem3"><a href="#">Mirum est notare quam littera gothica, quam nunc putamus parum claram, anteposuerit litterarum formas humanitatis per seacula quarta decima et quinta decima. Eodem modo typi, qui nunc nobis videntur parum clari, fiant sollemnes </a></li>--}}
                                {{--</ul>--}}
                            {{--</li>--}}
                            <li class="item5"><a><img src="{{ asset('images/arrow.png') }}" alt="">Characteristics</a>
                                <table style="display: none" class="contents5 table table-hover">
                                    {{ Widget::run('filter', ['tpl' => 'widgets.filtersShopProduct', 'filter' => $filters, 'category' => null,]) }}
                                </table>
                            </li>
                        </ul>
                    </div>
                    @if($prod->content != null)
                    <div style="border-top: 1px solid black; max-height: 100%;">
                        <h4 style="margin-bottom: 1%">Additional Information:</h4>
                    </div>
                    @endif
                        {!! $prod->content !!} {{-- Отображает, учитывая теги HTML--}}

                    @if(count($products) > 0)
                        <div class="latestproducts">
                            <div class="text-center">
                                <h2>Similar Products</h2> {{--или для сопутствующих товаров--}}
                            </div>
                            <div class="product-one">
                                @include('shop.include.cardProduct')
                                <div class="clearfix"></div>
                            </div>

                        </div>
                    @endif
                </div>
                {{--{{ Widget::run('filter', ['tpl' => 'widgets.filtersShop', 'filter' => null,]) }}--}}
                {{--<div class="clearfix"> </div>--}}
            </div>
        </div>
    </div>

@endsection