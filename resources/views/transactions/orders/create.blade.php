@php use Illuminate\Support\Facades\Session; @endphp
@extends('layout.app')
@section('content')
    <form method="POST" action="{{ route('orders.store') }}" enctype="multipart/form-data" id="orderForm">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Employee <span class="required"> * </span></label>
                <input type="text" class="form-control" value="{{ $current_employee['full_name'] }}" readonly>
                <input type="hidden" name="employee_id" value="{{ $current_employee['id'] }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Date <span class="required"> * </span></label>
                <input class="form-control" type="datetime-local" name="date" id="date" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Distributor <span class="required"> * </span></label>
                <select class="form-control select2-overwrite" name="distributor_id"
                        onchange="selectDistributor('hidden_div', this)">
                    <option value="">Select Distributor</option>
                    @foreach($distributors as $key => $distributor)
                        <option value="{{$key}}">
                            {{ $distributor }}
                        </option>
                    @endforeach
                </select>
                @error('distributor_id')
                <span class="text-danger error">{{ $errors->first('distributor_id') }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3" id="hidden_div">
                <label class="form-label">Product <span class="required"> * </span></label>
                <select class="form-control select2-overwrite" id="multiple-select" multiple="multiple">
                    <option value="">Select Product</option>
                </select>
                <input type="hidden" name="products" id="products">
                @error('products')
                <div class="text-danger error">{{ $errors->first('products') }}</div>
                @enderror
            </div>
        </div>
        <div id="product-details-container" class="row" style="display: none;">
            <!-- Dynamic product details will be inserted here -->
        </div>
        <div class="row">
            <div class="col-md-6 mb-3 ">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                    @foreach($statuses as $key => $status)
                        <option value="{{$key}}">
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
                @error('status')
                <div class="text-danger error">{{ $errors->first('status') }}</div>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Note</label>
                <input type="email" class="form-control" name="note" value="{{ old('note') }}">
                @error('note')
                <div class="text-danger error">{{ $errors->first('note') }}</div>
                @enderror
            </div>
        </div>
        <input type="hidden" name="action" id="actionType" value="save">
        <button type="submit" class="btn btn-primary me-3">Save</button>
        <button type="submit" class="btn btn-secondary me-3" onclick="setAction('save_and_close')">
            Save and Close
        </button>
        <span>Or</span>
        <a type="submit" class="link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
           href="{{ route('orders') }}">Cancel</a>
    </form>
    <script src="{{ asset('/js/jquery-3.7.1.min.js') }}"></script>
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
                var distributor_id = element.value;
                var url = '{{ url('api/v1/products/get') }}' + '?distributor=' + distributor_id;
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (response) {
                        $('#multiple-select').empty();
                        if (response.data && response.data.length > 0) {
                            response.data.forEach(function (product) {
                                $('#multiple-select').append(
                                    `<option value="${product.id}" data-name="${product.name}">${product.name}</option>`
                                );
                            });
                            $('#product-details-container').show();
                        } else {
                            // No products available
                            $('#multiple-select').append('<option value="">No products available</option>');
                        }
                        $('#multiple-select').select2({
                            placeholder: "Search and select an option",
                            allowClear: true,
                            theme: 'bootstrap-5',
                            width: '100%',
                            closeOnSelect: false
                        });
                    }
                });
            } else {
                document.getElementById(divId).style.display = 'none';
                $('#product-details-container').hide();
            }
        }

        $(document).ready(function () {
            $('.select2-overwrite').select2({
                placeholder: "Search and select an option",
                allowClear: true,
                theme: 'bootstrap-5',
                width: '100%'
            });
        });

        $('#orderForm').on('submit', function (e) {
            e.preventDefault();
            const selectedProducts = $('#multiple-select').val();
            const productsArray = [];

            selectedProducts.forEach(function (productId) {
                const quantity = $(`#quantity-${productId}`).val();
                const price = $(`#price-${productId}`).val();

                if (quantity && price) {
                    productsArray.push({
                        id: productId,
                        quantity: parseInt(quantity, 10),
                        price: parseFloat(price),
                    });
                }
            });
            $('#products').val(JSON.stringify(productsArray));
            this.submit();
        })
        $('#multiple-select').on('change', function () {
            var selectedProducts = $(this).val();
            $('#product-details-container').empty();
            selectedProducts.forEach(function (productId) {
                var productOption = $("#multiple-select option[value='" + productId + "']");
                var productName = productOption.data('name');
                $('#product-details-container').append(`
                    <div class="row product-row">
                        <div class="col-auto mb-3 d-flex align-items-end">
                        <label class="form-label fw-bold fs-4">${productName}</label>
                        </div>
                        <div class="col mb-3 " id="product-${productId}">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity-${productId}" placeholder="Quantity">
                        </div>
                        <div class="col mb-3 " id="product-${productId}">
                             <label class="form-label">Price per product (thousands)</label>
                            <input type="number" class="form-control mt-2" id="price-${productId}" placeholder="Price">
                        </div>
                    </div>
                `);
            });
        });

        const now = new Date();

        const timezoneOffset = now.getTimezoneOffset();
        const formattedDate = now.toISOString().slice(0, 16);
        // Set the default value
        document.getElementById('date').value = formattedDate;

        function setAction(action) {
            document.getElementById('actionType').value = action;
        }
    </script>
@endsection
