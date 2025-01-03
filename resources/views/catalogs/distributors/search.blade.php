@foreach($distributors as $distributor)
    <tr class="text-nowrap hover-pointer" id="delete-id-{{ $distributor['id'] }}"
        onclick="window.location='{{ route('distributors.edit', $distributor['id']) }}'">
        <td class="text-center">
            <input type="checkbox" name="ids" class="checkbox-ids" value="{{ $distributor['id'] }}">
        </td>
        <td>
            {{ $distributor['id'] }}
        </td>
        <td>
            {{ $distributor['name'] }}
        </td>
        <td>
            {{ $distributor['address'] }}
        </td>
        <td>
            {{ $distributor['country'] }}
        </td>
        <td>
            {{ $distributor['phone'] }}
        </td>
        <td>
            {{ $distributor['email'] }}
        </td>
        <td>
            {{ $distributor['created_at'] }}
        </td>
        <td>
            {{ $distributor['updated_at'] }}
        </td>
    </tr>
@endforeach
