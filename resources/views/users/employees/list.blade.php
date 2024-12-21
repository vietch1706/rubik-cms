@extends('layout.app')
@section('content')
    {{--    <link rel="stylesheet" href="{{ asset('css/list.css') }}">--}}
    <div class="container-fluid">
        <div class="action-buttons">
            <a href="{{ route('employees.create') }}" class="btn btn-primary">Create</a>
            <button id="delete-record" class="btn btn-danger">Delete Selected</button>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover ">
                <thead>
                <tr class="text-nowrap col-md">
                    <th scope="col" class="px-3">
                        <input type="checkbox" name="" id="select-all-ids">
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
                        Phone
                    </th>
                    <th scope="col" class="pe-5">
                        Email
                    </th>
                    <th scope="col" class="pe-5">
                        Salary (million)
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
                @if(!empty($employees))
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
                            <td>
                                {{ $employee['address'] }}
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
                @endif
                </tbody>
            </table>
        </div>
        <div class="pagination-container">{{$employees->links()}}</div>
    </div>
    <script src="{{ asset('/js/jQuery.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    <script type="text/javascript">
        @if (Session::has('success'))
        Swal.fire(
            '{{ Session::get('success') }}',
            '',
            'success'
        );
        @endif

        $(function (e) {
            $('input[name="ids"]').click(function (e) {
                e.stopPropagation();
            });

            $('#select-all-ids').click(function () {
                $('.checkbox-ids').prop('checked', $(this).prop('checked'));
            });

            $('#delete-record').click(function (e) {
                e.preventDefault();

                var selectedIds = [];
                $('input[name="ids"]:checked').each(function () {
                    selectedIds.push($(this).val());
                });
                if (selectedIds.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Customers Selected',
                        text: 'Please select at least one customer to delete.',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this action!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, keep it',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Perform delete action here
                        $.ajax({
                            url: "{{ route('employees.destroy') }}",
                            type: "DELETE",
                            data: {
                                ids: selectedIds,
                                _token: "{{ csrf_token() }}",
                            },
                            success: function (response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Your selected employees have been deleted.',
                                    'success'
                                );
                                $.each(selectedIds, function (key, val) {
                                    $('#delete-id-' + val).remove();
                                });
                                window.location.reload();
                            },
                            error: function () {
                                Swal.fire(
                                    'Error!',
                                    'There was a problem deleting the employees.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
