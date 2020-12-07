<h1>
    @if (isset($title)) {{$title}} @endif
</h1>
<ol class="breadcrumb">
    <li><a href="{{route('index.index')}}"><i class="fa fa-dashboard"></i>{{$parent}}</a> </li>
    @if (isset($order))
        <li><a href="{{route('orders.index')}}"><i></i>{{$order}}</a></li>
    @endif
    @if (isset($category))
        <li><a href="{{route('categories.index')}}"><i></i>{{$category}}</a></li>
    @endif
    @if (isset($user))
        <li><a href="{{route('users.index')}}"><i></i>{{$user}}</a></li>
    @endif
    @if (isset($product))
        <li><a href="{{route('products.index')}}"><i></i>{{$product}}</a></li>
    @endif
    @if (isset($groups_filter))
        <li><a href="{{url('/admin/filter/groups-filter')}}"><i></i>{{$groups_filter}}</a></li>
    @endif
    @if (isset($values_filter))
        <li><a href="{{url('/admin/filter/values-filter')}}"><i></i>{{$values_filter}}</a></li>
    @endif
    @if (isset($currency))
        <li><a href="{{url('/admin/currency/index')}}"><i></i>{{$currency}}</a></li>
    @endif
    @if (isset($active))
    <li><i class="active">{{ $active }}</i></li>
    @endif
</ol>