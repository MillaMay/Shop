<!-- В файле my.css пока прописано 5 ступеней вложенности для показа -->
@foreach($items as $item)
    <p class="item-p">
        <a href="{{ route('categories.edit', $item->id) }}"
           class="list-group-item">{{ $item->title }}</a>
        <span>
            @if(!$item->hasChildren())
                <a href="{{ url("/admin/categories/mydelete?id=$item->id") }}" class="deletebd" title="Удалить из БД">
                    <i class="fa fa-fw fa-close text-danger"></i>
                </a>
            @else
                <i class="fa fa-fw fa-close" title="Эта категория имеет подкатегорию"></i>
            @endif
        </span>
    </p>
    @if($item->hasChildren())
        <div class="list-group">
            @include(env('THEME').'shop.admin.category.menu.customMenuItems',
            ['items' => $item->children()])
        </div>
    @endif
@endforeach