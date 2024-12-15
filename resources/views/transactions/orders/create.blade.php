@php use App\Http\Controllers\Transactions\OrdersController;use App\Models\Catalogs\Products;use App\Models\Transactions\Orders;use Illuminate\Support\Facades\Session; @endphp
@extends('layout.app')
@section('content')
    <form method="POST" action="{{ route('orders.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Distributor <span class="required"> * </span></label>
                <select class="form-control select2" name="distributor_id"
                        onchange="selectDistributor('hidden_div', this)">
                    <option value="">Select Distributor</option>
                    @foreach($distributors as $key => $distributor)
                        <option value="{{$key}}" @if($key == old('distributor_id')) selected @endif>
                            {{ $distributor }}
                        </option>
                    @endforeach
                </select>
                @error('distributor_id')
                <span class="text-danger error">{{ $errors->first('distributor_id') }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Employee <span class="required"> * </span></label>
                <input type="text" class="form-control" name="employee_id" value="{{ $current_employee }}" readonly>
            </div>
        </div>
        <div class="row" id="hidden_div">
            <div class="col mb-3">
                <label class="form-label">Product <span class="required"> * </span></label>
                {{--                <select class="form-control select2" name="product_id">--}}
                {{--                    <option value="">Select Product</option>--}}
                {{--                    @foreach($products as $key => $product)--}}
                {{--                        <option value="{{$key}}" @if($key == old('product_id')) selected @endif>--}}
                {{--                            {{ $product }}--}}
                {{--                        </option>--}}
                {{--                    @endforeach--}}
                {{--                </select>--}}
                @error('product_id')
                <span class="text-danger error">{{ $errors->first('product_id') }}</span>
                @enderror
            </div>
        </div>
        <input type="hidden" name="action" id="actionType" value="save">
        <button type="submit" class="btn btn-primary me-3">Save</button>
        <button type="submit" class="btn btn-secondary me-3" onclick="setAction('save_and_close')">Save and Close
        </button>
        <span>Or</span>
        <a type="submit" class="link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
           href="{{ route('orders') }}">Cancel</a>
    </form>

    <script src="{{ asset('/js/jquery.js') }}"></script>
    <script>
        @if (Session::has('success'))
        Swal.fire(
            '{{ Session::get('success') }}',
            '',
            'success'
        );
        @endif
        function selectDistributor(divId, element) {
            if (element.value != '') {
                document.getElementById(divId).style.display = 'block';
            } else {
                document.getElementById(divId).style.display = 'none';
            }
            {{ Products::getProductByDistributorId(1) }}
        }

        $(document).ready(function () {
            $('.select2').select2({
                placeholder: "Search and select an option",
                allowClear: true,
                theme: 'bootstrap-5',
                width: '100%'
            });
        });

        function setAction(action) {
            document.getElementById('actionType').value = action;
        }
    </script>
@endsection
