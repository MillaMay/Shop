<tbody>
    @foreach($groups as $group_id => $group_item)
    <tr>
        <td>{{ $group_item }}</td>
        @if(!empty($attrs[$group_id]))
            @foreach($attrs[$group_id] as $attr_id => $value)
                @if(!empty($filter) && in_array($attr_id, $filter))
                    <td>{{ $value }}</td>
                @endif
            @endforeach
        @endif
    </tr>
    @endforeach
</tbody>