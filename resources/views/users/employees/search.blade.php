@foreach($employees as $employee)
    <tr class="text-nowrap hover-pointer" id="delete-id-{{$employee['id']}}"
        onclick="window.location='{{ route('employees.edit', $employee['id']) }}'">
        <td class="text-center">
            <input type="checkbox" name="ids" class="checkbox-ids"
                   value="{{ $employee['user_id'] }}">
        </td>
        <td>
            {{ $employee['id'] }}
        </td>
        <td>
            {{ $employee['first_name'] }}
        </td>
        <td>
            {{ $employee['last_name'] }}
        </td>
        <td>
            {{ $employee['gender'] }}
        </td>
        <td>
            {{ $employee['phone'] }}
        </td>
        <td>
            {{ $employee['email'] }}
        </td>
        <td>
            {{ $employee['salary'] }}
        </td>
        <td title="{{ $employee['address'] }}">
            {{ Str::limit($employee['address'], 50) }}
        </td>
        <td>
            @if($employee['avatar'])
                <img src="{{ url($employee['avatar']) }}"
                     alt="{{ str_replace('/storage/avatars/', '', $employee['avatar']) }}"
                     width="75"
                     height="50">
            @endif
        </td>
        <td class=" h-100 {{ $class = $employee['is_activated'] != 0 ? 'text-success' : 'text-danger' }}">
            <i class="fa-solid fa-circle"></i>
            {{ $employee['is_activated'] != 0 ? 'Activated' : 'Inactive' }}
        </td>
        <td>
            {{ $employee['activated_at'] }}
        </td>
        <td>
            {{ $employee['created_at'] }}
        </td>
        <td>
            {{ $employee['updated_at'] }}
        </td>
    </tr>
@endforeach
