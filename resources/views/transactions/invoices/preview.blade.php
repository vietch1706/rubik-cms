@extends('layout.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}" type="text/css">
    <form enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">ID </label>
                <input type="text" class="form-control" value="{{ $invoice['id'] }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Date </label>
                <input type="datetime-local" class="form-control" value="{{ $invoice['date'] }}" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Employee </label>
                <input type="text" class="form-control" value="{{ current($invoice['employee']) }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Customer </label>
                <input type="text" class="form-control" value="{{ current($invoice['customer']) }}" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Status</label>
                <input type="text" class="form-control"
                       value="{{
                    $invoice['status'] === 0 ? 'Pending' :
                   ($invoice['status'] === 1 ? 'Payment Confirm' :
                   ($invoice['status'] === 2 ? 'Processing' :
                   ($invoice['status'] === 3 ? 'Shipped' :
                   ($invoice['status'] === 4 ? 'Delivered' :
                   ($invoice['status'] === 5 ? 'Completed' :
                   ($invoice['status'] === 6 ? 'Cancelled' :
                   ($invoice['status'] === 7 ? 'Refunded' : '')))))))
               }}" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Note </label>
                <div class="form-control"
                     style="white-space: pre-wrap; word-wrap: break-word; background-color: #E9ECEF">
                    {!! $invoice['note'] !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Created At </label>
                <input type="datetime-local" class="form-control" value="{{ $invoice['created_at'] }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Updated At </label>
                <input type="datetime-local" class="form-control" value="{{ $invoice['updated_at'] }}" readonly>
            </div>
        </div>
        <div class="container-fluid pb-4 px-0">
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
                    @foreach($invoiceDetails as $invoiceDetail)
                        <tr class="text-nowrap hover-pointer">
                            <td>
                                {{ $invoiceDetail['id'] }}
                            </td>
                            <td>
                                {{ current($invoiceDetail['product']) }}
                            </td>
                            <td>
                                {{ $invoiceDetail['price'] }}
                            </td>
                            <td>
                                {{ $invoiceDetail['quantity'] }}
                            </td>
                            <td>
                                {{ $invoiceDetail['created_at'] }}
                            </td>
                            <td>
                                {{ $invoiceDetail['updated_at'] }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-between">
            <div class="left-item">
                <a type="submit" class="link-secondary mx-3" href="{{ route('invoices') }}">Return</a>
            </div>
            <div class="right-item">
                <button class="delete-item" data-id="{{ $invoice['id'] }}">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </div>
        </div>
    </form>
    <script src="{{ asset('js/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('/js/jquery-3.7.1.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.delete-item').on('click', function (event) {
                event.preventDefault();
                var selectedIds = [];
                var selectedId = $(this).data('id');
                selectedIds.push(selectedId);
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
                            url: "{{ route('invoices.destroy') }}",
                            type: "DELETE",
                            data: {
                                ids: selectedIds,
                                _token: "{{ csrf_token() }}",
                            },
                            success: function (response) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Your selected invoice have been deleted.',
                                    icon: 'success'
                                }).then(function () {
                                    window.location.href = "{{ route('invoices') }}";
                                });
                            },
                            error: function (response) {
                                console.log(response);
                                Swal.fire(
                                    'Error!',
                                    'There was a problem deleting the invoice.',
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
