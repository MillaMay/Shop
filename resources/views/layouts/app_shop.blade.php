<!DOCTYPE html>
<html>
<head>
    <title>{!! MetaTag::tag('title') !!}</title>
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet" type="text/css" media="all" />
    <!--jQuery(necessary for Bootstrap's JavaScript plugins)-->
    <script src="{{ asset('js/jquery-1.11.0.min.js') }}"></script>
    <!--Custom-Theme-files-->
    <!--theme-style-->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css" media="all" />
    <!--//theme-style-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="Luxury Watches Responsive web template, Bootstrap Web Templates, Flat Web Templates, Andriod Compatible web template,
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design" />
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    <!--start-menu-->
    <script src="{{ asset('js/simpleCart.min.js') }}"> </script>
    <link href="{{ asset('css/memenu.css') }}" rel="stylesheet" type="text/css" media="all" />
    <script type="text/javascript" src="{{ asset('js/memenu.js') }}"></script>
    <script>$(document).ready(function(){$(".memenu").memenu();});</script>
    <!--dropdown-->
    <script src="{{ asset('js/jquery.easydropdown.js') }}"></script>

    <style> html {overflow-x: hidden;} </style>
</head>
<body>

<!--top-header-->
<div class="top-header">
    <div class="container">
        <div class="top-header-main">
            <div class="col-md-8 top-header-left">
                <div class="drop">
                    <div class="box">
                        <select tabindex="4" class="dropdown drop" onchange="window.location.href=this.options[this.selectedIndex].value">
                            {{--onchange="..." - для того, чтобы в option сработала ссылка--}}
                            <option value="" class="label">{{ App\Services\CurrencyConversion::getCurrencyCode() }} :</option> {{--по умолчанию USD (при чистой сессии)--}}
                            @foreach($currency_all as $curr)
                                <option value="{{ route('currency', $curr->code) }}">{{ $curr->code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="box1">
                        <select tabindex="4" class="dropdown">
                            <option value="" class="label">English :</option>
                            <option value="1">English</option>
                            <option value="2">Russian</option>
                        </select>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-md-2 top-header-left">
                <div class="cart box_1" style="margin-top: 3%">
                    <a href="{{ route('basket') }}" title="Your Basket">
                        {{--<div class="total">--}}
                            {{--<span class="simpleCart_total"></span>--}}
                        {{--</div>--}}
                        <img src="{{ asset('images/cart-1.png') }}" alt="" />
                    </a>
                    {{--<p><a href="javascript:;" class="simpleCart_empty">Empty Cart</a></p>--}}
                    <div class="clearfix"> </div>
                </div>
            </div>
            <div class="col-md-2 top-header-left" style="padding-left: 5%">
                @if (Route::has('login'))
                    <div class="links container">
                        @auth
                            {{-- Здесь прописан правильный порядок (if else) для оптимизации запроса при LOGIN--}}

                            {{--@if(Auth::user()->isVisitor())--}}
                            {{--<strong> <a href="{{ url('/') }}" style="color: white; text-decoration: none">Shop</a></strong>--}}
                            @if(Auth::user()->isAdministrator())
                                <div>
                                    <a href="{{ url('/admin/index') }}" style="color: white; text-decoration: none; cursor: pointer">Admin panel</a>
                                </div>
                            @elseif(Auth::user()->isUser())
                                <div>
                                    <a href="{{ route('users', $user_id) }}" style="color: white; text-decoration: none">My Account</a>
                                </div>
                            @elseif(Auth::user()->isDisabled())
                                <strong> <a href="{{ url('/') }}" style="color: white; text-decoration: none">Shop</a></strong>

                            @endif
                            <div>
                                <a class="dropdown-item" href="{{ route ('logout') }}" style="color: white; text-decoration: none" onclick="event.preventDefault();
document.getElementById('logout-form').submit();">Logout</a>
                            </div>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none">
                                @csrf
                            </form>
                        @else
                            <div class="drop">
                                <a href="{{ route('login') }}" style="color: white; text-decoration: none; border: none">LOGIN</a>
                            </div>
                        @endauth
                    </div>
                @endif
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<!--top-header-->

<!--start-logo-->
<div class="logo">
    <h1>{!! MetaTag::tag('h1') !!}</h1>
</div>
<!--start-logo-->

<!--bottom-header-->
<div class="header-bottom">
    <div class="container">
        <div class="header">
            <div class="col-md-9 header-left">
                <div class="top-nav">
                    <ul class="memenu skyblue">
                        <li class="active">
                            @if(Auth::user())
                                <a href="{{ route('home') }}">Home</a>
                            @else
                                <a href="{{ url('/') }}">Home</a>
                            @endif
                        </li>

                        @if(isset($menu))
                        @include('shop.include.menuCategories', ['items' => $menu->roots()])
                        @endif

                        <li class="grid"><a href="{{ route('all-products') }}" style="color: #8996A8">All Products</a></li>
                    </ul>
                </div>
                <div class="clearfix"> </div>
            </div>
            <div class="col-md-3 header-right">
                <div class="search-bar">
                    <form action="{{ url('/search') }}" method="get" autocomplete="off">
                        <input id="search" name="search" type="text" class="form-control" placeholder="Search" style="color: black">
                        <input type="submit" value="">
                    </form>
                </div>
            </div>
            <div class="clearfix"> </div>
        </div>
    </div>
</div>
<!--bottom-header-->

{{--Для сообщений--}}
<div class="div-message">
    @if (count($errors) > 0){{--withErrors--}}
        @foreach ($errors->all() as $error)
            <div class="text-center alert alert-info">{{ $error }}</div>
        @endforeach
    @endif
</div>
{{--Для сообщений--}}
@if(session()->has('info'))
    <h2 class="text-center alert alert-info">{{ session()->get('info') }}</h2>
@endif
{{-----------------}}

@yield('content')

<!--information-starts-->
<div class="information">
    <div class="container">
        <div class="infor-top">
            <div class="col-md-5 infor-left">
                <h3>Follow Us</h3>
                <ul>
                    <li><a href="https://www.facebook.com/eva2heavenly/" target="_blank"><span class="fb"></span><h6>Facebook</h6></a></li>
                    <li style="margin-left: 1%"><a href="#"><img src="{{ asset('images/icon-inst.png') }}"><h6>Instagram</h6></a></li>
                    <li><a href="#"><span class="twit"></span><h6>Twitter</h6></a></li>
                    {{--<li><a href="#"><span class="google"></span><h6>Google+</h6></a></li>--}}
                </ul>
            </div>
            <div class="col-md-5 infor-left">
                <h3>More Information</h3>
                <ul>
                    <li><a href="#"><p>New Products</p></a></li>
                    <li><a href="#"><p>Discount Products</p></a></li>
                    <li><a href="#"><p>Hit of sales</p></a></li>
                    <li><a href="#"><p>Blog</p></a></li>
                </ul>
            </div>
            <div class="col-md-2 infor-left">
                <h3>Store Information</h3>
                <h4>
                    <p>@if(Auth::user())
                            <a href="{{ route('home') }}">SHOP LUXURY WATCHES</a>
                        @else
                            <a href="{{ url('/') }}">SHOP LUXURY WATCHES</a>
                        @endif</p><br>
                    Chernivtsi city, Shkolnaya street, 11/4</h4>
                <h5>+38(066)828-74-77</h5>
                <p><a href="mailto:millamay2017@gmail.com">millamay2017@gmail.com</a></p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!--information-end-->

<!--footer-starts-->
<div class="footer">
    <div class="container">
        <div class="footer-top">
            <div class="col-md-6 footer-left">
                <form action="contact.html">
                    <input type="submit" value="Contact Us">
                </form>
            </div>
            <div class="col-md-6 footer-right">
                <p>© 2020 Web Developer
                    <a href="https://www.linkedin.com/in/mila-ganieva" target="_blank" style="color: #337ab7">Milla</a>
                </p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!--footer-end-->

<script src="{{asset('js/myShop.js')}}"></script>

<!-- Search -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
<script type="text/javascript">
    var route = "{{ url('/autocomplete') }}";
    $('#search').typeahead ({
        source: function (term, process) {
            return $.get(route, { term: term }, function (data) {
                return process(data);
            });
        }
    });
</script>

</body>
</html>