@extends('layout.app')
@section('content')
    <style>
        /* General Styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9fafc;
            color: #343a40;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 20px;
        }

        .action-buttons a, .action-buttons button {
            margin-right: 30px;
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .action-buttons a {
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
        }

        .action-buttons a:hover {
            background-color: #0056b3;
        }

        .action-buttons button {
            background-color: #dc3545;
            color: #fff;
            border: none;
        }

        .action-buttons button:hover {
            background-color: #a71d2a;
        }

        /* Table Styling */
        .table-responsive {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }

        div.scroll-row {
            overflow-x: scroll;
            width: 100em;
        }

        .table {
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            display: block;
            white-space: nowrap;
            height: 67vh;
        }

        .table thead {
            background-color: #343a40;
            color: #fff;
        }

        .table thead th {
            top: 0;
            z-index: 2;
            text-align: left;
            padding: 10px;
            white-space: nowrap;
        }

        .table tbody tr:hover {
            background-color: #f1f3f5;
        }

        .table tbody td {
            padding: 10px;
            vertical-align: middle;
        }

        /* Checkbox Styling */
        input[type="checkbox"] {
            transform: scale(1.2);
            margin-right: 10px;
        }

        /* Status Styling */
        .text-success {
            color: #28a745 !important;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .hover-pointer {
            cursor: pointer;
        }

        /* Pagination Container */
        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        /* Pagination Styling */
        .pagination {
            margin-top: 15px;
            justify-content: center;
            display: flex;
            list-style: none;
            padding: 0;
            border-radius: 5px;
        }

        .pagination .page-item {
            margin: 0 5px;
        }

        .pagination .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 500;
            color: #57534e;
            background-color: #eff1f5;
            border: 1px solid #dcd7ba;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s, box-shadow 0.3s;
            text-decoration: none;
        }

        /* Hover Effect */
        .pagination .page-item .page-link:hover {
            background-color: #a6adc8; /* Latte Overlay0 */
            color: #f5e0dc; /* Latte Rosewater */
            box-shadow: 0px 2px 6px rgba(166, 173, 200, 0.5); /* Latte Subtle Glow */
        }

        /* Active State */
        .pagination .page-item.active .page-link {
            background-color: #dce0e8; /* Latte Mantle */
            color: #7287fd; /* Latte Blue */
            border-color: #7287fd; /* Match Latte Blue */
            pointer-events: none;
            box-shadow: 0px 2px 6px rgba(114, 135, 253, 0.5); /* Active Glow */
        }

        /* Disabled State */
        .pagination .page-item.disabled .page-link {
            color: #6c757d; /* Latte Subtext */
            background-color: #eff1f5; /* Latte Base */
            border-color: #dcd7ba; /* Latte Overlay */
            pointer-events: none;
        }

        .small.text-muted {
            margin-top: 0;
            padding: 0;
            display: inline;
        }

    </style>
    <div class="action-buttons">
        <a href="{{ route('customers.create') }}" class="btn btn-primary">Create</a>
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
                            <input type="checkbox" name="ids" class="checkbox-ids" value="{{ $customer['user_id'] }}">
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
                        <td>
                            {{ $customer['address'] }}
                        </td>
                        <td>
                            @if($customer['avatar'])
                                <img src="{{ url($customer['avatar']) }}"
                                     alt="{{ str_replace('/storage/avatars/', '', $customer['avatar']) }}" width="75"
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
            @endif
            </tbody>
        </table>
    </div>
    <div class="pagination-container">{{$customers->links()}}</div>
    <script src="{{ asset('/js/jquery.js') }}"></script>
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
                                window.location.reload();

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
    </script>
@endsection
