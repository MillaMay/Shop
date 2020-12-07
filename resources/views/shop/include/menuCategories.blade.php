{{--Здесь пока прописано 5 ступеней вложенности для показа--}}
@foreach($items as $item)
    <li class="grid"><a href="{{ route('category', $item->id) }}">{{ $item->title }}</a>
        <div class="mepanel" style="width: 50%">
            <div class="row">
                <div class="col1 me-one">
                    {{--<h4>Категории из категории {{ $item->title }}</h4>--}}
                    <ul>
                        @if($item->hasChildren())
                            @foreach($item->children() as $child1)
                                <li><a href="{{ route('category', $child1->id) }}">{{ $child1->title }}</a></li>
                                @if($child1->hasChildren())
                                    @foreach($child1->children() as $child2)
                                        <li style="margin-left: 15%"><a href="{{ route('category', $child2->id) }}">{{ $child2->title }}</a></li>
                                        @if($child2->hasChildren())
                                            @foreach($child2->children() as $child3)
                                                <li style="margin-left: 30%"><a href="{{ route('category', $child3->id) }}">{{ $child3->title }}</a></li>
                                                @if($child3->hasChildren())
                                                    @foreach($child3->children() as $child4)
                                                        <li style="margin-left: 45%"><a href="{{ route('category', $child4->id) }}">{{ $child4->title }}</a></li>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </li>
@endforeach