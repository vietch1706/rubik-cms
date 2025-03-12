@foreach($categories as $category)
    <tr class="text-nowrap hover-pointer" id="delete-id-{{ $category['id'] }}"
        onclick="window.location='{{ route('categories.edit', $category['id']) }}'">
        <td class="text-center">
            <input type="checkbox" name="ids" class="checkbox-ids" value="{{ $category['id'] }}">
        </td>
        <td>
            {{ $category['id'] }}
        </td>
        <td>
            {{ $category['name'] }}
        </td>
        <td>
            {{ $category['slug'] }}
        </td>
        @if($category['parent_category'])
            <td>
                {{ current($category['parent_category']) }}
            </td>
        @else
            <td class="text-danger" style="font-weight: bold; font-size: 20px;">
                Parent
            </td>
        @endif
        <td>
            {{ $category['created_at'] }}
        </td>
        <td>
            {{ $category['updated_at'] }}
        </td>
    </tr>
@endforeach
