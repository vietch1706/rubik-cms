@extends('layout.app')
@section('content')
    <form enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">ID </label>
                <input type="text" class="form-control" value="{{ $orders['id'] }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Order Number </label>
                <input type="text" class="form-control" value="{{ $orders['order_number'] }}" readonly>
            </div>
        </div>
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
                <textarea class="form-control" rows="5" readonly>{{ $orders['note'] }}</textarea>
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
        <ul id="orderTab" role="tablist" @class(['nav', 'nav-tabs','d-none' => !$isImported])>
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="order-details-tab" data-bs-toggle="tab"
                        data-bs-target="#order-details" type="button" role="tab"
                        aria-controls="order-details" aria-selected="true" href="#">Order Details
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="partially-imported-tab" data-bs-toggle="tab"
                        data-bs-target="#partially-imported" type="button" role="tab"
                        aria-controls="partially-imported" aria-selected="false">
                    Partially Imported Products
                </button>
            </li>
        </ul>
        <div class="tab-content" id="orderTabContent">
            <div class="tab-pane fade show active" id="order-details" role="tabpanel"
                 aria-labelledby="order-details-tab">
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
                                    SKU
                                </th>
                                <th scope="col" class="pe-5">
                                    Status
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
                                        {{ $orderDetail['sku'] }}
                                    </td>
                                    <td @class([
                                'h-100',
                                'text-primary' => $orderDetail['status'] === 0,
                                'text-warning' => $orderDetail['status'] === 1,
                                'text-success' => $orderDetail['status'] === 2,
                            ])>
                                        <i class="fa-solid fa-circle"></i>
                                        {{
                                            [
                                                0 => 'Pending',
                                                1 => 'Partially Imported',
                                                2 => 'Fully Imported',
                                            ][$orderDetail['status']]
                                        }}
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
            </div>
            <div class="tab-pane fade" id="partially-imported" role="tabpanel" aria-labelledby="partially-imported-tab">
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
                                    SKU
                                </th>
                                <th scope="col" class="pe-5">
                                    Status
                                </th>
                                <th scope="col" class="pe-5">
                                    Price (thousands)
                                </th>
                                <th scope="col" class="pe-5">
                                    Quantity
                                </th>
                                <th scope="col" class="pe-5">
                                    Imported Quantity
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
                                @if($orderDetail['status'] === 1)
                                    <tr class="text-nowrap hover-pointer">
                                        <td>
                                            {{ $orderDetail['id'] }}
                                        </td>
                                        <td>
                                            {{ current($orderDetail['product']) }}
                                        </td>
                                        <td>
                                            {{ $orderDetail['sku'] }}
                                        </td>
                                        <td class="text-warning">Partially Imported</td>
                                        <td>
                                            {{ $orderDetail['price'] }}
                                        </td>
                                        <td>
                                            {{ $orderDetail['quantity'] }}
                                        </td>
                                        <td>
                                            {{ $orderDetail['imported_quantity'] }}
                                        </td>
                                        <td>
                                            {{ $orderDetail['created_at'] }}
                                        </td>
                                        <td>
                                            {{ $orderDetail['updated_at'] }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <a type="submit" class="btn btn-secondary me-3" href="{{ route('orders') }}">Return</a>
    </form>
@endsection
