@extends('layouts.app_shop')

@section('content')

<!--banner-starts-->
<div class="bnr" id="home">
    <div  id="top" class="callbacks_container">
        <ul class="rslides" id="slider4">
            <li>
                <img src="{{ asset('images/index/bnr-1.jpg') }}" alt=""/>
            </li>
            <li>
                <img src="{{ asset('images/index/bnr-2.jpg') }}" alt=""/>
            </li>
            <li>
                <img src="{{ asset('images/index/bnr-3.jpg') }}" alt=""/>
            </li>
        </ul>
    </div>
    <div class="clearfix"> </div>
</div>
<!--banner-ends-->

<!--Slider-Starts-Here-->
<script src="{{ asset('js/responsiveslides.min.js') }}"></script>
<script>
    // You can also use "$(window).load(function() {"
    $(function () {
        // Slideshow 4
        $("#slider4").responsiveSlides({
            auto: true,
            pager: true,
            nav: true,
            speed: 500,
            namespace: "callbacks",
            before: function () {
                $('.events').append("<li>before event fired.</li>");
            },
            after: function () {
                $('.events').append("<li>after event fired.</li>");
            }
        });

    });
</script>
<!--End-slider-script-->

<!--about-starts-->
<div class="about">
    <div class="container">
        <div class="about-top grid-1">
            <div class="col-md-4 about-left">
                <figure class="effect-bubba">
                    <img class="img-responsive" src="{{ asset('images/index/abt-1.jpg') }}" alt=""/>
                    <figcaption>
                        <h2>We always have discounts</h2>
                        <p>In sit amet sapien eros Integer dolore magna aliqua</p>
                    </figcaption>
                </figure>
            </div>
            <div class="col-md-4 about-left">
                <figure class="effect-bubba">
                    <img class="img-responsive" src="{{ asset('images/index/abt-2.jpg') }}" alt=""/>
                    <figcaption>
                        <h4>We have the best offers</h4>
                        <p>In sit amet sapien eros Integer dolore magna aliqua</p>
                    </figcaption>
                </figure>
            </div>
            <div class="col-md-4 about-left">
                <figure class="effect-bubba">
                    <img class="img-responsive" src="{{ asset('images/index/abt-3.jpg') }}" alt=""/>
                    <figcaption>
                        <h4>We always have new items</h4>
                        <p>In sit amet sapien eros Integer dolore magna aliqua</p>
                    </figcaption>
                </figure>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!--about-end-->

<!--product-starts-->
<div class="text-center">
    <h2>New Products</h2>
</div>
<div class="product">
    <div class="container">
        <div class="product-top">
            <div class="product-one">
                @include('shop.include.cardProduct')
                <div class="clearfix"></div>
            </div>
            <form action="{{ route('all-products') }}">
                <div class="address text-center" style="margin-top: 0">
                    <input type="submit" value="view all products">
                </div>
            </form>
        </div>
    </div>
</div>
<!--product-end-->

@endsection