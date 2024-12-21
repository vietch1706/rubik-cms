@extends('layout.app')
@section('content')
    <div class="container-fluid">
        <div class="action-buttons">
            <a href="{{ route('receipts.import') }}" class="btn btn-primary">Import</a>
            <button id="delete-record" class="btn btn-danger">Delete Selected</button>
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
                        Order ID
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
                            {{ $importReceipt['order_id'] }}
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
                                'text-warning' => $importReceipt['status'] === 1,
                                'text-success' => $importReceipt['status'] === 2,
                                'text-danger' => !in_array($importReceipt['status'], [0, 1, 2 ]),
                            ])>
                            <i class="fa-solid fa-circle"></i>
                            {{
                                $importReceipt['status'] === 0 ? 'Pending' :
                                ($importReceipt['status'] === 1 ? 'Partially Imported' :
                                ($importReceipt['status'] === 2 ? 'Fully Imported' :
                                'Failed'))
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
                        title: 'No Orders Selected',
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
                            url: "{{ route('orders.destroy') }}",
                            type: "DELETE",
                            data: {
                                ids: selectedIds,
                                _token: "{{ csrf_token() }}",
                            },
                            success: function (response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Your selected orders have been deleted.',
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
                                    'There was a problem deleting the orders.',
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
