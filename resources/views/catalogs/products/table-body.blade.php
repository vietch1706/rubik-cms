@forelse ($products as $product)
    <tr class="bg-white border-b border-latte-surface0 hover:bg-latte-surface1">
        <td class="px-3 py-4">
            <input type="checkbox" name="ids[]" value="{{ $product['id'] }}"
                class="product-checkbox focus:ring-latte-lavender">
        </td>
        <td class="px-5 py-4">{{ $product['id'] }}</td>
        <td class="px-5 py-4">{{ $product['name'] }}</td>
        <td class="px-5 py-4">
            @if ($product['image'])
                <img src="{{ asset('storage/' . $product['image']) }}" alt="{{ $product['name'] }}"
                    class="w-12 h-12 object-cover rounded border border-latte-surface1">
            @else
                <span class="text-latte-subtext0">No Image</span>
            @endif
        </td>
        <td class="px-5 py-4">{{ $product['sku'] }}</td>
        <td class="px-5 py-4">
            @if ($product['release_date'])
                {{ \Carbon\Carbon::parse($product['release_date'])->format('Y-m-d') }}
            @else
                -
            @endif
        </td>
        <td class="px-5 py-4">{{ $product['brand'] ? reset($product['brand']) : '-' }}</td>
        <td class="px-5 py-4">{{ $product['distributor'] ? reset($product['distributor']) : '-' }}</td>
        <td class="px-5 py-4">{{ $product['category'] ? reset($product['category']) : '-' }}</td>
        <td class="px-5 py-4">{{ $product['slug'] }}</td>
        <td class="px-5 py-4">
            <span
                class="px-2 py-1 rounded text-xs font-medium flex items-center gap-1
                {{ $product['status'] === 1 ? 'bg-latte-green text-white' : 'bg-latte-red text-white' }}">
                <i class="fa-regular fa-circle"></i>
                {{ $product['status'] === 1 ? 'Available' : 'Unavailable' }}
            </span>
        </td>
        <td class="px-5 py-4">{{ number_format($product['price'] / 1000, 2) }}</td>
        <td class="px-5 py-4">{{ $product['quantity'] }}</td>
        <td class="px-5 py-4">{{ $product['magnetic'] ? 'Yes' : 'No' }}</td>
        <td class="px-5 py-4">{{ $product['weight'] }}</td>
        <td class="px-5 py-4">{{ $product['box_weight'] }}</td>
        <td class="px-5 py-4">{{ $product['created_at']->format('Y-m-d H:i') }}</td>
        <td class="px-5 py-4">{{ $product['updated_at']->format('Y-m-d H:i') }}</td>
        <td class="px-5 py-4">
            <div class="flex space-x-2">
                <a href="{{ route('admin.catalogs.products.edit', $product['id']) }}"
                    class="text-latte-blue hover:text-latte-sapphire transition-colors duration-200">
                    <i class="fa-solid fa-edit"></i>
                </a>
                <button class="delete-product text-latte-red hover:text-latte-maroon transition-colors duration-200"
                    data-id="{{ $product['id'] }}">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="19" class="px-5 py-4 text-center text-latte-subtext0">
            No products found.
        </td>
    </tr>
@endforelse
