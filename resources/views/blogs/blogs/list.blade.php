@extends('layout.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/list.css') }}" type="text/css">
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <div class="action-buttons">
                <a href="{{ route('blogs.create') }}" class="btn btn-primary py-2"><i class="fa-solid fa-plus"></i>
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
            <table class="table table-bordered table-striped table-hover">
                <thead>
                <tr class="text-nowrap col-md">
                    <th scope="col" class="px-3">
                        <input type="checkbox" name="" id="select-all-ids">
                    </th>
                    <th scope="col" class="pe-3 ">
                        ID
                    </th>
                    <th scope="col" class="pe-3 col-2">
                        Employee
                    </th>
                    <th scope="col" class="pe-3 col-2">
                        Title
                    </th>
                    <th scope="col" class="pe-3 col-2">
                        Slug
                    </th>
                    <th scope="col" class="pe-3 col-2">
                        Topic
                    </th>
                    <th scope="col" class="pe-3 col-2">
                        Date
                    </th>
                    <th scope="col" class="pe-3 col-2">
                        Created At
                    </th>
                    <th scope="col" class="pe-3 col-2">
                        Updated At
                    </th>
                </tr>
                </thead>
                @if(!empty($blogs))
                    <tbody>
                    @foreach($blogs as $blog)
                        <tr class="text-nowrap hover-pointer" id="delete-id-{{ $blog['id'] }}"
                            onclick="window.location='{{ route('blogs.edit', $blog['id']) }}'">
                            <td class="text-center">
                                <input type="checkbox" name="ids" class="checkbox-ids" value="{{ $blog['id'] }}">
                            </td>
                            <td>
                                {{ $blog['id'] }}
                            </td>
                            <td>
                                {{ current($blog['employee_id']) }}
                            </td>
                            <td>
                                {{ $blog['title'] }}
                            </td>
                            <td>
                                {{ $blog['slug'] }}
                            </td>
                            <td>
                                {{ $blog['topic'] }}
                            </td>
                            <td>
                                {{ $blog['date'] }}
                            </td>
                            <td>
                                {{ $blog['created_at'] }}
                            </td>
                            <td>
                                {{ $blog['updated_at'] }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
            </table>
        </div>
        <div class="pagination-container">{!! $link !!}</div>
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
                        title: 'No Blog Selected',
                        text: 'Please select at least one blog to delete.',
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
                            url: "{{ route('blogs.destroy') }}",
                            type: "DELETE",
                            data: {
                                ids: selectedIds,
                                _token: "{{ csrf_token() }}",
                            },
                            success: function (response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Your selected blogs have been deleted.',
                                    'success'
                                );
                                $.each(selectedIds, function (key, val) {
                                    $('#delete-id-' + val).remove();
                                });
                            },
                            error: function () {
                                Swal.fire(
                                    'Error!',
                                    'There was a problem deleting the blogs.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
            $('#search').on('keyup', function (e) {
                e.preventDefault();
                let searchString = $(this).val();
                console.log(searchString);
                $.ajax({
                    url: "{{ route('blogs.search') }}",
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
                            $('tbody').html(response.blogs);
                            $('.pagination-container').html(response.pagination);
                        }
                    },

                })
            });
        });
    </script>
@endsection
