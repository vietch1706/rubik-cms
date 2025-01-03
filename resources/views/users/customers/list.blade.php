@extends('layout.app')
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <div class="action-buttons">
                <a href="{{ route('customers.create') }}" class="btn btn-primary py-2"><i class="fa-solid fa-plus"></i>
                    Create</a>
                <button id="delete-record" class="btn btn-danger py-2"><i class="fa-solid fa-trash-can"></i> Delete
                </button>
            </div>
            <div class="input-group w-25">
                <span class="input-group-text" id="basic-addon1">
                 <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input
                    type="text"
                    id="search"
                    class="search-box form-control h-100 py-2 pl-5"
                    placeholder="Search Here ..."
                    autocomplete="off"
                    aria-describedby="basic-addon2">
            </div>
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
                        Identity Number
                    </th>
                    <th scope="col" class="pe-5">
                        Type
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
                @if(!empty($customers))
                    @foreach($customers as $customer)
                        <tr class="text-nowrap hover-pointer" id="delete-id-{{$customer['id']}}"
                            onclick="window.location='{{ route('customers.edit', $customer['id']) }}'">
                            <td class="text-center">
                                <input type="checkbox" name="ids" class="checkbox-ids"
                                       value="{{ $customer['id'] }}">
                            </td>
                            <td>
                                {{ $customer['id'] }}
                            </td>
                            <td>
                                {{ $customer['first_name'] }}
                            </td>
                            <td>
                                {{ $customer['last_name'] }}
                            </td>
                            <td>
                                {{ $customer['gender'] }}
                            </td>
                            <td>
                                {{ $customer['phone'] }}
                            </td>
                            <td>
                                {{ $customer['email'] }}
                            </td>
                            <td>
                                {{ $customer['identity_number'] }}
                            </td>
                            <td>
                                {{ $customer['type'] }}
                            </td>
                            <td title="{{ $customer['address'] }}">
                                {{ Str::limit($customer['address'], 50) }}
                            </td>
                            <td>
                                @if($customer['avatar'])
                                    <img src="{{ url($customer['avatar']) }}"
                                         alt="{{ str_replace('/storage/avatars/', '', $customer['avatar']) }}"
                                         width="75"
                                         height="50">
                                @endif
                            </td>
                            <td class=" h-100 {{ $class = $customer['is_activated'] != 0 ? 'text-success' : 'text-danger' }}">
                                <i class="fa-solid fa-circle"></i>
                                {{ $customer['is_activated'] != 0 ? 'Activated' : 'Inactive' }}
                            </td>
                            <td>
                                {{ $customer['activated_at'] }}
                            </td>
                            <td>
                                {{ $customer['created_at'] }}
                            </td>
                            <td>
                                {{ $customer['updated_at'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="table-error"></div>
        </div>
        <div class="pagination-container">{{$customers->links()}}</div>
        @endif
    </div>
    <script src="{{ asset('/js/jquery-3.7.1.min.js') }}"></script>
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
                        $.ajax({
                            url: "{{ route('customers.destroy') }}",
                            type: "DELETE",
                            data: {
                                ids: selectedIds,
                                _token: "{{ csrf_token() }}",
                            },
                            success: function (response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Your selected customers have been deleted.',
                                    'success'
                                );
                                $.each(selectedIds, function (key, val) {
                                    $('#delete-id-' + val).remove();
                                });

                            },
                            error: function () {
                                Swal.fire(
                                    'Error!',
                                    'There was a problem deleting the customers.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
        $('#search').on('keyup', function (e) {
            e.preventDefault();
            let searchString = $(this).val();
            $.ajax({
                url: "{{ route('customers.search') }}",
                method: 'GET',
                data: {'search': searchString},
                success: function (response) {
                    console.log(response.error)
                    if (response.error) {
                        $('tbody').html(
                            `<tr><td colspan="16" class="text-danger text-center" style="font-size: 20px;">${response.error}</td></tr>`
                        );
                        $('.pagination-container').html('');
                    } else {
                        $('tbody').html(response.customers);
                        $('.pagination-container').html(response.pagination);
                    }
                },

            })
        });
    </script>
@endsection
