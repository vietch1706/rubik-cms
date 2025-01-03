@foreach($products as $product)
    <tr class="text-nowrap hover-pointer" id="delete-id-{{ $product['id'] }}"
        onclick="window.location='{{ route('products.edit', $product['id']) }}'">
        <td class="text-center">
            <input type="checkbox" name="ids" class="checkbox-ids" value="{{ $product['id'] }}">
        </td>
        <td>
            {{ $product['id'] }}
        </td>
        <td>
            {{ $product['name'] }}
        </td>
        <td>
            @if($product['image'])
                <img src="{{ url($product['image']) }}"
                     alt="{{ $product['slug'] }}" width="75"
                     height="50">
            @endif
        </td>
        <td>{{ $product['sku'] }}</td>
        <td>{{ $product['release_date'] }}</td>
        <td>
            @if($product['brand'])
                {{ current($product['brand']) }}
            @endif
        </td>
        <td>
            @if($product['distributor'])
                {{ current($product['distributor']) }}
            @endif
        </td>
        <td>
            @if($product['category'])
                {{ current($product['category']) }}
            @endif
        </td>
        <td>{{ $product['slug'] }}</td>
        <td class=" h-100 {{ $class = $product['status'] != 0 ? 'text-success' : 'text-danger' }}">
            <i class="fa-solid fa-circle"></i>
            {{ $product['status'] != 0 ? 'Available' : 'Unavailable' }}
        </td>
        <td>{{ $product['price'] }}</td>
        <td>{{ $product['quantity'] }}</td>
        <td>{{ $product['magnetic'] }}</td>
        <td>{{ $product['weight'] }}</td>
        <td>{{ $product['box_weight'] }}</td>
        <td>
            {{ $product['created_at'] }}
        </td>
        <td>
            {{ $product['updated_at'] }}
        </td>
    </tr>
@endforeach
