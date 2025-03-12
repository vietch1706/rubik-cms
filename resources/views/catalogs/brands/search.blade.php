@foreach($brands as $brand)
    <tr class="text-nowrap hover-pointer" id="delete-id-{{ $brand['id'] }}"
        onclick="window.location='{{ route('brands.edit', $brand['id']) }}'">
        <td class="text-center">
            <input type="checkbox" name="ids" class="checkbox-ids" value="{{ $brand['id'] }}">
        </td>
        <td>
            {{ $brand['id'] }}
        </td>
        <td>
            {{ $brand['name'] }}
        </td>
        <td>
            {{ $brand['slug'] }}
        </td>
        <td>
            @if($brand['image'])
                <img src="{{ url($brand['image']) }}"
                     alt="{{ $brand['slug'] }}" width="75"
                     height="50">
            @endif
        </td>
        <td>
            {{ $brand['created_at'] }}
        </td>
        <td>
            {{ $brand['updated_at'] }}
        </td>
    </tr>
@endforeach
