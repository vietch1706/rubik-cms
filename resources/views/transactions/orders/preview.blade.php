@extends('layout.app')
@section('content')
    <form enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Employee </label>
                <input type="text" class="form-control" value="{{ current($orders['employee']) }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Distributor </label>
                <input type="text" class="form-control" value="{{ current($orders['distributor']) }}" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Date </label>
                <input type="datetime-local" class="form-control" value="{{ $orders['date'] }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Status </label>
                <input type="text" class="form-control" value="{{
                                $orders['status'] === 0 ? 'Pending' :
                                ($orders['status'] === 1 ? 'Processing' :
                                ($orders['status'] === 2 ? 'Completed' :
                                'Canceled'))
                           }}" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Note </label>
                <input type="text" class="form-control" value="{{ $orders['note'] }}" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Created At </label>
                <input type="datetime-local" class="form-control" value="{{ $orders['created_at'] }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Updated At </label>
                <input type="datetime-local" class="form-control" value="{{ $orders['updated_at'] }}" readonly>
            </div>
        </div>
        <div class="container-fluid pb-5 px-0">
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
                    @foreach($orderDetails as $orderDetail)
                        <tr class="text-nowrap hover-pointer">
                            <td>
                                {{ $orderDetail['id'] }}
                            </td>
                            <td>
                                {{ current($orderDetail['product']) }}
                            </td>
                            <td>
                                {{ $orderDetail['price'] }}
                            </td>
                            <td>
                                {{ $orderDetail['quantity'] }}
                            </td>
                            <td>
                                {{ $orderDetail['created_at'] }}
                            </td>
                            <td>
                                {{ $orderDetail['updated_at'] }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <a type="submit" class="btn btn-secondary me-3" href="{{ route('orders') }}">Return</a>
    </form>
@endsection
