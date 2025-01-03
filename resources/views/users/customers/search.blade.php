@foreach($customers as $customer)
    <tr class="text-nowrap hover-pointer" id="delete-id-{{$customer['id']}}"
        onclick="window.location='{{ route('customers.edit', $customer['id']) }}'">
        <td class="text-center">
            <input type="checkbox" name="ids" class="checkbox-ids"
                   value="{{ $customer['user_id'] }}">
        </td>
        <td>{{ $customer['id'] }}</td>
        <td>{{ $customer['first_name'] }}</td>
        <td>{{ $customer['last_name'] }}</td>
        <td>{{ $customer['gender'] }}</td>
        <td>{{ $customer['phone'] }}</td>
        <td>{{ $customer['email'] }}</td>
        <td>{{ $customer['identity_number'] }}</td>
        <td>{{ $customer['type'] }}</td>
        <td title="{{ $customer['address'] }}">{{ Str::limit($customer['address'], 50) }}</td>
        <td>
            @if($customer['avatar'])
                <img src="{{ url($customer['avatar']) }}" alt="{{ basename($customer['avatar']) }}" width="75"
                     height="50">
            @endif
        </td>
        <td class="h-100 {{ $class = $customer['is_activated'] != 0 ? 'text-success' : 'text-danger' }}">
            <i class="fa-solid fa-circle"></i>
            {{ $customer['is_activated'] != 0 ? 'Activated' : 'Inactive' }}
        </td>
        <td>{{ $customer['activated_at'] }}</td>
        <td>{{ $customer['created_at'] }}</td>
        <td>{{ $customer['updated_at'] }}</td>
    </tr>
@endforeach
