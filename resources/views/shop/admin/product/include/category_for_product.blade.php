@foreach($categories as $category_list)
    <option value="{{ $category_list->id }}"
        @isset($product->id)
            @if($category_list->id == $product->category_id)
            selected
            @endif
            @if($product->category_id == $category_list->id) @endif
        @endisset
    >
        {!! $delimiter ?? "" !!} {{ $category_list->title ?? "" }}
    </option>

    @if(count($category_list->children) > 0)
    @include('shop.admin.product.include.category_for_product', [
    'categories' => $category_list->children,
    'delimiter' => ' - ' . $delimiter,
    'product' => $product,
    ])
    @endif
@endforeach