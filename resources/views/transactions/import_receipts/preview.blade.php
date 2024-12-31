@extends('layout.app')
@section('content')
    <form action="{{ route('receipts.approve', ['id' => $importReceipts['id']]) }}" method="POST"
          enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Order Number </label>
                <input type="text" class="form-control" value="{{ $importReceipts['order_number'] }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Employee </label>
                <input type="text" class="form-control" value="{{ current($importReceipts['employee']) }}" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Date </label>
                <input type="datetime-local" class="form-control" value="{{ $importReceipts['date'] }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Status </label>
                <input type="text" class="form-control" value="{{
                                $importReceipts['status'] === 0 ? 'Pending' : 'Complete'
                           }}" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Created At </label>
                <input type="datetime-local" class="form-control" value="{{ $importReceipts['created_at'] }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Updated At </label>
                <input type="datetime-local" class="form-control" value="{{ $importReceipts['updated_at'] }}" readonly>
            </div>
        </div>
        <div @class(['container-fluid', 'pb-5','px-0', 'd-none' => $importReceiptDetails->isEmpty()])>
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
        <button type="submit" @class(['btn', 'btn-primary', 'd-none' =>  !empty($importReceiptDetail)])>Approve
            Receipt
        </button>
        <a class="btn btn-secondary me-3" href="{{ route('receipts') }}">Return</a>
    </form>
    <script src="{{ asset('/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2@11.js') }}"></script>
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

    </script>
@endsection
