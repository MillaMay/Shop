@foreach($products as $product)
    <div class="col-md-4 product-left p-left" style="min-height: 470px;"> {{-- установлена высота для выравнивания блока--}}
        <div class="product-main simpleCart_shelfItem">
            <a href="{{ route('product', $product->id) }}" class="mask">
                @if(empty($product->img))
                    <img class="img-responsive zoom-img" src="{{ asset('/images/no_image.png') }}" />
                @else
                <img class="img-responsive zoom-img" src="{{ asset("/uploads/single/$product->img") }}" alt="" />
                @endif
            </a>
            <div class="product-bottom">
                <a href="{{ route('product', $product->id) }}" class="mask"><h3>{{ $product->title }}</h3></a>

                @if($product->status == 1)
                    <p>Explore Now</p>
                    <h4><form action="{{ route('basket-add', $product->id) }}" method="POST">
                            <button type="submit" class="item_add mask" title="Add To Cart" style="background: none; padding-left: 0;">
                                <i></i><span class="item_price">
                                {{ number_format($product->price * $currency->value, null, null, " ") }} {{ App\Services\CurrencyConversion::getCurrencyCode() }}</span>
                                @if($product->old_price > $product->price)
                                    <small>
                                        <del>{{ number_format($product->old_price * $currency->value, null, null, " ") }} {{ App\Services\CurrencyConversion::getCurrencyCode() }}</del>
                                    </small>
                                @endif
                            </button>
                            @csrf
                        </form></h4>
                @else
                    <p style="margin-bottom: 3%">Not available yet...</p>
                    <span class="item_price">
                            {{ number_format($product->price * $currency->value, null, null, " ") }} {{ App\Services\CurrencyConversion::getCurrencyCode() }}</span>
                    @if($product->old_price > $product->price)
                        <small>
                            <del>{{ number_format($product->old_price * $currency->value, null, null, " ") }} {{ App\Services\CurrencyConversion::getCurrencyCode() }}</del>
                        </small>
                    @endif
                @endif

            </div>
            @if(isset($product->old_price) && $product->price < $product->old_price)
                @php
                    $result = (($product->old_price - $product->price) / $product->old_price) * 100;
                    $result = round($result, 2);
                @endphp
                <div class="srch" style="top: 22%;">
                    <span>- {{ $result }}%</span>
                </div>
            @endif
            @if($product->hit)
                <div class="srch" style="top: 0; right: 13px; display: block; font-size: 8px; padding: 1px 3px;">
                    <span class="label pull-right" style="border-radius: 0; padding: 3px 7px; background: #337ab7;">Hit of sales</span>
                </div>
            @endif
        </div>
    </div>
@endforeach
