@foreach($campaigns as $campaign)
    <tr class="text-nowrap hover-pointer" id="delete-id-{{ $campaign['id'] }}"
        onclick="window.location='{{ route('campaigns.edit', $campaign['id']) }}'">
        <td class="text-center">
            <input type="checkbox" name="ids" class="checkbox-ids" value="{{ $campaign['id'] }}">
        </td>
        <td>
            {{ $campaign['id'] }}
        </td>
        <td>
            {{ $campaign['name'] }}
        </td>
        <td>
            {{ $campaign['slug'] }}
        </td>
        <td>
            {{ $campaign['start_date'] }}
        </td>
        <td>
            {{ $campaign['end_date'] }}
        </td>
        <td>
            {{ $campaign['created_at'] }}
        </td>
        <td>
            {{ $campaign['updated_at'] }}
        </td>
    </tr>
@endforeach
