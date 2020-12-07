<form action="{{ isset($category) ? route('category-filters', $category->id) : route('filters') }}" method="GET">
<div class="col-md-3 prdt-right" id="attrs">
    @if(isset($category))
    <h5 class="text-center" style="margin-top: 0; color: #555555;">Filters from category {{ $category->title }}</h5>
    @endif
    <div class="w_sidebar">
        <div style="margin: 0 0 10% 1.5%;">
            <label for="price_from">Price from
                <input type="text" name="price_from" id="price_form" size="6" value="{{ request()->price_from }}"
                       title="The price can be either an integer or a floating point number" placeholder="0.00">
            </label>
            <label for="price_to">to
                <input type="text" name="price_to" id="price_to" size="6" value="{{ request()->price_to }}"
                       title="The price can be either an integer or a floating point number" placeholder="0.00">
            </label>
        </div>
        <section  class="sky-form">
            @foreach($groups as $group_id => $group_item)
            <h4>{{ $group_item }}</h4>
            <div class="row1 scroll-pane">
                @if(!empty($attrs[$group_id]))
                    @foreach($attrs[$group_id] as $attr_id => $value)
                        <div class="col col-4">
                            <label class="checkbox">
                                {{--Для сохранения чекбоксов--}}
                                @php $checks = []; @endphp
                                @if(request()->input('attrs'))
                                    @foreach(request()->input('attrs') as $attrs_id)
                                        @foreach($attrs_id as $check)
                                            @php $checks[] = $check; @endphp
                                            {{-----------------------------}}
                                        @endforeach
                                    @endforeach
                                    <input type="checkbox" name="attrs[{{ $group_id }}][]" value="{{ $attr_id }}"
                                    @if(in_array($attr_id, $checks)) checked @endif>
                                @else
                                    <input type="checkbox" name="attrs[{{ $group_id }}][]" value="{{ $attr_id }}">
                                @endif
                                <i></i>{{ $value }}
                            </label>
                        </div>
                    @endforeach
                @endif
            </div>
            @endforeach
        </section>
    </div>
    <div class="d-inline" style="margin-bottom: 3%; display: inline">
        <div class="address">
            <input type="submit" value="Reset all filters" id="reset-attrs" style="float: left">
        </div>
        <div class="address">
            <input type="submit" value="Apply" style="float: right">
        </div>
    </div>
</div>
</form>