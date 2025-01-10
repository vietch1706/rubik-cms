@extends('layout.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/list.css') }}" type="text/css">
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <div class="action-buttons">
                <a href="{{ route('employees.create') }}" class="btn btn-primary py-2"><i class="fa-solid fa-plus"></i>
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
                @if(!empty($employees))
                    <tbody>
                    @foreach($employees as $employee)
                        <tr class="text-nowrap hover-pointer" id="delete-id-{{$employee['id']}}"
                            onclick="window.location='{{ route('employees.edit', $employee['id']) }}'">
                            <td class="text-center">
                                <input type="checkbox" name="ids" class="checkbox-ids"
                                       value="{{ $employee['id'] }}">
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
                    </tbody>
            </table>
        </div>
        <div class="pagination-container">{{$employees->links()}}</div>
        @endif
    </div>
    <script src="{{ asset('js/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('/js/jquery-3.7.1.min.js') }}"></script>
    <script>
        @if (Session::has('success'))
        Swal.fire(
            '{{ Session::get('success') }}',
            '',
            'success'
        );
        @endif
        $('th').click(function () {
            var table = $(this).parents('table').eq(0)
            var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()))
            this.asc = !this.asc
            if (!this.asc) {
                rows = rows.reverse()
            }
            for (var i = 0; i < rows.length; i++) {
                table.append(rows[i])
            }
            $('th i').remove();
            $(this).append(this.asc ? ' <i class="fa-solid fa-arrow-up-short-wide"></i>' : ' <i class="fa-solid fa-arrow-down-short-wide"></i> ');
        })

        function comparer(index) {
            return function (a, b) {
                var valA = getCellValue(a, index), valB = getCellValue(b, index)
                return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB)
            }
        }

        function getCellValue(row, index) {
            return $(row).children('td').eq(index).text()
        }

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
        $('#search').on('keyup', function (e) {
            e.preventDefault();
            let searchString = $(this).val();
            console.log(searchString);
            $.ajax({
                url: "{{ route('employees.search') }}",
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
                        $('tbody').html(response.employees);
                        $('.pagination-container').html(response.pagination);
                    }
                },

            })
        });
    </script>
@endsection
