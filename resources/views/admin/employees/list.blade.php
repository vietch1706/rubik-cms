@extends('layout.app')
@section('content')
    <style>
        .table {
            display: block;
            white-space: nowrap;
        }

        .hover-pointer {
            cursor: pointer;
        }
    </style>
    <div class="action-buttons">
        <a href="{{ route('users.create') }}" class="btn btn-primary">Create User</a>
        <button id="delete-record" class="btn btn-danger">Delete Selected</button>
    </div>
    <div class="table-responsive ">
        <table class="table table-bordered table-striped table-hover h-100 table-responsive-xl overflow-auto">
            <thead>
            <tr class="text-nowrap col-md">
                <th scope="col" class="px-3">
                    <input type="checkbox" name="" id="select-all-ids" style="margin:3px 3px 3px 4px">
                </th>
                <th scope="col" class="pe-5">
                    ID
                </th>
                <th scope="col" class="pe-5">
                    First Name
                </th>
                <th scope="col" class="pe-5">
                    Last Name
                </th>
                <th scope="col" class="pe-5">
                    Gender
                </th>
                <th scope="col" class="pe-5">
                    Phone Number
                </th>
                <th scope="col" class="pe-5">
                    Email
                </th>
                <th scope="col" class="pe-5">
                    Address
                </th>
                <th scope="col" class="pe-5">
                    Avatar
                </th>
                <th scope="col" class="pe-5">
                    Is Activated
                </th>
                <th scope="col" class="pe-5">
                    Activated At
                </th>
                <th scope="col" class="pe-5">
                    Created At
                </th>
                <th scope="col" class="pe-5">
                    Updated At
                </th>
            </tr>
            </thead>
            <tbody>
            {{--            @if(!empty($users))--}}
            {{--                @foreach($users as $user)--}}
            {{--                    <tr class="text-nowrap hover-pointer" id="delete-id-{{$user['id']}}"--}}
            {{--                        onclick="window.location='{{ route('users.edit', $user['id']) }}'">--}}
            {{--                        <td class="text-center">--}}
            {{--                            <input type="checkbox" name="ids" class="checkbox-ids" value="{{ $user['id'] }}"--}}
            {{--                                   style="margin:3px 3px 3px 4px">--}}
            {{--                        </td>--}}
            {{--                        <td>--}}
            {{--                            {{ $user['id'] }}--}}
            {{--                        </td>--}}
            {{--                        <td>--}}
            {{--                            {{ $user['role']->name }}--}}
            {{--                        </td>--}}
            {{--                        <td>--}}
            {{--                            {{ $user['first_name'] }}--}}
            {{--                        </td>--}}
            {{--                        <td>--}}
            {{--                            {{ $user['last_name'] }}--}}
            {{--                        </td>--}}
            {{--                        <td>--}}
            {{--                            {{ $user['gender'] }}--}}
            {{--                        </td>--}}
            {{--                        <td>--}}
            {{--                            {{ $user['phone'] }}--}}
            {{--                        </td>--}}
            {{--                        <td>--}}
            {{--                            {{ $user['email'] }}--}}
            {{--                        </td>--}}
            {{--                        <td>--}}
            {{--                            {{ $user['address'] }}--}}
            {{--                        </td>--}}
            {{--                        <td>--}}
            {{--                            {{ $user['avatar'] }}--}}
            {{--                        </td>--}}
            {{--                        <td class=" h-100 {{ $class = $user['is_activated'] != 0 ? 'text-success' : 'text-danger' }}">--}}
            {{--                            <i class="fa-solid fa-circle"></i>--}}
            {{--                            {{ $user['is_activated'] != 0 ? 'Activated' : 'Inactive' }}--}}
            {{--                        </td>--}}
            {{--                        <td>--}}
            {{--                            {{ $user['activated_at'] }}--}}
            {{--                        </td>--}}
            {{--                        <td>--}}
            {{--                            {{ $user['created_at'] }}--}}
            {{--                        </td>--}}
            {{--                        <td>--}}
            {{--                            {{ $user['updated_at'] }}--}}
            {{--                        </td>--}}
            {{--                    </tr>--}}
            {{--                @endforeach--}}
            {{--            @endif--}}
            </tbody>
        </table>
        {{--        {{$users->links()}}--}}
    </div>
    <script>
        $(function (e) {
            $('input[name="ids"]').click(function (e) {
                e.stopPropagation();  // Prevent the click from propagating to the row
            });
            $("#select-all-ids").click(function () {
                $('.checkbox-ids').prop('checked', $(this).prop('checked'));
            });
            $("#delete-record").click(function (e) {
                e.preventDefault();
                var allIds = [];
                $('input:checkbox[name=ids]:checked').each(function () {
                    allIds.push($(this).val());
                });
                $.ajax({
                    url: "{{ route('users.destroy') }}",
                    type: "DELETE",
                    data: {
                        ids: allIds,
                        _token: '{{csrf_token()}}',
                    },
                    success: function (response) {
                        $.each(allIds, function (key, val) {
                            $('#delete-id-' + val).remove();
                        })
                    }
                })
            })
        });
    </script>

@endsection
