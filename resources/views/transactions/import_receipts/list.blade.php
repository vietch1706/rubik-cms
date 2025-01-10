@extends('layout.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/list.css') }}" type="text/css">
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <div class="action-buttons">
                <a href="{{ route('import') }}" class="btn btn-primary py-2"><i class="fa-solid fa-upload"></i>
                    Import</a>
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
                    <th scope="col" class="px-3" style="width: 25px; height: 25px; text-align: center;">
                        <input class="checkbox-size" type="checkbox" name=""
                               id="select-all-ids">
                    </th>
                    <th scope="col" class="pe-5">
                        ID
                    </th>
                    <th scope="col" class="pe-5">
                        Order Number
                    </th>
                    <th scope="col" class="pe-5">
                        Employee
                    </th>
                    <th scope="col" class="pe-5">
                        Date
                    </th>
                    <th scope="col" class="pe-5">
                        Status
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
                @foreach($importReceipts as $importReceipt)
                    <tr class="text-nowrap hover-pointer" id="delete-id-{{$importReceipt['id']}}"
                        onclick="window.location='{{ route('receipts.preview', $importReceipt['id']) }}'">
                        <td class="text-center">
                            <input type="checkbox" name="ids" class="checkbox-ids"
                                   value="{{ $importReceipt['id'] }}">
                        </td>
                        <td>
                            {{ $importReceipt['id'] }}
                        </td>
                        <td>
                            {{ $importReceipt['order_number'] }}
                        </td>
                        @if($importReceipt['employee'])
                            <td>
                                {{ current($importReceipt['employee']) }}
                            </td>
                        @else
                            <td>

                            </td>
                        @endif
                        <td>
                            {{ $importReceipt['date'] }}
                        </td>
                        <td @class([
                                'h-100',
                                'text-primary' => $importReceipt['status'] === 0,
                                'text-success' => $importReceipt['status'] === 1,
                                'text-danger' => $importReceipt['status'] === 2,
                            ])>
                            <i class="fa-solid fa-circle"></i>
                            {{
                               $importReceipt['status'] === 0 ? 'Pending' : ($importReceipt['status'] === 1 ? 'Complete' : 'Cancelled')
                            }}
                        </td>
                        <td>
                            {{ $importReceipt['created_at'] }}
                        </td>
                        <td>
                            {{ $importReceipt['updated_at'] }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination-container">{{$importReceipts->links()}}</div>
    </div>
    <script src="{{ asset('js/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('/js/jquery-3.7.1.min.js') }}"></script>
    <script type="text/javascript">
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
                        title: 'No Receipts Selected',
                        text: 'Please select at least one receipt to delete.',
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
                            url: "{{ route('receipts.destroy') }}",
                            type: "DELETE",
                            data: {
                                ids: selectedIds,
                                _token: "{{ csrf_token() }}",
                            },
                            success: function (response) {
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                );
                                $.each(selectedIds, function (key, val) {
                                    $('#delete-id-' + val).remove();
                                });
                            },
                            error: function () {
                                Swal.fire(
                                    'Error!',
                                    'There was a problem deleting the receipts.',
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
