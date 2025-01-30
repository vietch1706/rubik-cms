@extends('layout.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}" type="text/css">
    <form action="{{ route('receipts.approve', ['id' => $importReceipt['id']]) }}" method="POST"
          enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Order Number </label>
                <input type="text" class="form-control" value="{{ $importReceipt['order_number'] }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Employee </label>
                <input type="text" class="form-control" value="{{ current($importReceipt['employee']) }}" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Date </label>
                <input type="datetime-local" class="form-control" value="{{ $importReceipt['date'] }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Status </label>
                <input type="text" class="form-control"
                       value="{{ $importReceipt['status'] === 0 ? 'Pending' : ($importReceipt['status'] === 1 ? 'Complete' : 'Cancelled') }}"
                       readonly>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Created At </label>
                <input type="datetime-local" class="form-control" value="{{ $importReceipt['created_at'] }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Updated At </label>
                <input type="datetime-local" class="form-control" value="{{ $importReceipt['updated_at'] }}" readonly>
            </div>
        </div>
        <div @class(['container-fluid', 'pb-4','px-0', 'd-none' => (empty($importReceiptDetails))])>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover ">
                    <thead>
                    <tr class="text-nowrap col-md">
                        <th scope="col" class="pe-5">
                            ID
                        </th>
                        <th scope="col" class="pe-5">
                            Product
                        </th>
                        <th scope="col" class="pe-5">
                            SKU
                        </th>
                        <th scope="col" class="pe-5">
                            Import Date
                        </th>
                        <th scope="col" class="pe-5">
                            Price (thousands)
                        </th>
                        <th scope="col" class="pe-5">
                            Quantity
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
                    @foreach($importReceiptDetails as $importReceiptDetail)
                        <tr class="text-nowrap hover-pointer">
                            <td>
                                {{ $importReceiptDetail['id'] }}
                            </td>
                            <td>
                                {{ current($importReceiptDetail['product']) }}
                            </td>
                            <td>
                                {{ $importReceiptDetail['sku'] }}
                            </td>
                            <td>
                                {{ $importReceiptDetail['import_date'] }}
                            </td>
                            <td>
                                {{ $importReceiptDetail['price'] }}
                            </td>
                            <td>
                                {{ $importReceiptDetail['quantity'] }}
                            </td>
                            <td>
                                {{ $importReceiptDetail['created_at'] }}
                            </td>
                            <td>
                                {{ $importReceiptDetail['updated_at'] }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-between">
            <div class="left-item">
                <button type="submit" @class(['btn', 'btn-primary', 'd-none' => $importReceipt['status']])>Approve
                    Receipt
                </button> &nbsp;
                <span @class(['d-none' => $importReceipt['status']])>Or</span> &nbsp;
                <a href="{{ route('receipts') }}" class="link-secondary">Return</a>
            </div>
            <div class="right-item">
                <button class="delete-item" data-id="{{ $importReceipt['id'] }}">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </div>
        </div>
    </form>
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
        @if (Session::has('error'))
        Swal.fire(
            '{{ Session::get('error') }}',
            '',
            'error'
        );
        @endif
        $(document).ready(function () {
            $('.delete-item').on('click', function (event) {
                event.preventDefault();
                var selectedIds = [];
                var selectedId = $(this).data('id');
                selectedIds.push(selectedId);
                console.log(selectedIds);

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
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Your selected receipt have been deleted.',
                                    icon: 'success'
                                }).then(function () {
                                    window.location.href = "{{ route('receipts') }}";
                                });
                            },
                            error: function (response) {
                                console.log(response);
                                Swal.fire(
                                    'Error!',
                                    'There was a problem deleting the receipt.',
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
