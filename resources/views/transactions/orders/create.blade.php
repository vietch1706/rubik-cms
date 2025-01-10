@php use App\Models\Transactions\Orders;use Illuminate\Support\Facades\Session; @endphp
@extends('layout.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}" type="text/css">
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
            <div class="col-md-6 mb-3">
                <label class="form-label">Distributor <span class="required"> * </span></label>
                <select class="form-control select2-overwrite" name="distributor_id" id="distributor_id">
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
            <div class="col-md-6 mb-3 ">
                <label class="form-label">Status</label>
                <input class="form-control" type="text"
                       value="{{ $statuses[Orders::STATUS_PENDING] }}" readonly>
                <input class="form-control" type="hidden" name="status" value="{{ Orders::STATUS_PENDING }}" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Note</label>
                <textarea class="form-control" name="note">{{ old('note') }}</textarea>
                @error('note')
                <div class="text-danger error">{{ $errors->first('note') }}</div>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="colmb-3">
                <label class="form-label">Add Product <span class="required"> * </span></label>
                <button type="button" class="btn btn-success" id="openModalButton">
                    Add Product
                </button>
                <br>
                <span id="validationError" class="text-danger error" style="display: none;">
                     Please select distributor before adding products.
                </span>
                @error('products')
                <span class="text-danger error">{{ $errors->first('products') }}</span>
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
        <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productModalLabel">Add Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="productDropdown" class="form-label">Product <span
                                    class="required"> * </span></label>
                            <select class="form-control select2-overwrite" id="productSelect">
                                <option value="">Select Product</option>
                            </select>
                            <span class="text-danger error" id="productSelect-error"></span>
                            <input type="hidden" name="products" id="products">
                        </div>
                        <div class="mb-3">
                            <label for="productQuantity" class="form-label">Quantity <span
                                    class="required"> * </span></label>
                            <input type="number" class="form-control" id="productQuantity">
                            <span class="text-danger error" id="productQuantity-error"></span>
                        </div>
                        <div class="mb-3">
                            <label for="productPrice" class="form-label">Price <span class="required"> * </span></label>
                            <input type="number" class="form-control" id="productPrice">
                            <span class="text-danger error" id="productPrice-error"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="addProduct()">Add Product</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="row mt-4">
        <div class="col-md-12 mb-3">
            <label class="form-label">Products</label>
            <table class="table table-bordered table-striped" id="productTable">
                <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
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
        $(document).ready(function () {
            $('.select2-overwrite').select2({
                placeholder: "Search and select an option",
                allowClear: true,
                theme: 'bootstrap-5',
                width: '100%'
            });
        });
        $(document).ready(function () {
            let products = [];
            $('#distributor_id').change(function () {
                var distributorId = $(this).val();
                if (distributorId) {
                    $('#productSelect').empty().append('<option value="">Select Product</option>');
                    var url = '{{ url('api/v1/products/get') }}' + '?distributor=' + distributorId;
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function (response) {
                            if (response.data && response.data.length > 0) {
                                response.data.forEach(function (product) {
                                    $('#productSelect').append(
                                        `<option value="${product.id}" data-name="${product.name}">${product.name}</option>`
                                    );
                                });
                            } else {
                                $('#productSelect').append('<option value="">No products available</option>');
                            }
                            $('#productSelect').select2({
                                placeholder: "Search and select an option",
                                allowClear: true,
                                theme: 'bootstrap-5',
                                width: '100%',
                                dropdownParent: $('#productModal')
                            });
                        },
                    });
                } else {
                    $('#productSelect').empty().append('<option value="">Select Product</option>');
                }
            });
            $('#openModalButton').on('click', function (e) {
                const isValid = validateFields();

                if (isValid) {
                    $('#productModal').modal('show');
                } else {
                    $('#validationError').show();
                    setTimeout(() => {
                        $('#validationError').fadeOut();
                    }, 3000);
                }
            });

            function validateFields() {
                const requiredField = $('#distributor_id');
                if (!requiredField.val().trim()) {
                    return false;
                }
                return true;
            }

            $('#productSelect').change(function () {
                const selectedOption = $(this).find('option:selected');

                $('#productPrice').val('');

                $('#productQuantity').val('');
            });

            window.addProduct = function () {
                $('.error').text('');
                const selectedProduct = $('#productSelect option:selected');
                const productId = selectedProduct.val();
                const productName = selectedProduct.data('name');
                const quantity = $('#productQuantity').val();
                const price = $('#productPrice').val();

                let hasError = false;

                if (!productId) {
                    $('#productSelect-error').text('Please select a product');
                    hasError = true;
                }
                if (!quantity) {
                    $('#productQuantity-error').text('Quantity is required');
                    hasError = true;
                } else if (quantity <= 0) {
                    $('#productQuantity-error').text('Quantity must be greater than 0');
                    hasError = true;
                }
                if (!price) {
                    $('#productPrice-error').text('Price is required');
                    hasError = true;
                } else if (price <= 0) {
                    $('#productPrice-error').text('Price must be greater than 0');
                    hasError = true;
                }
                if (hasError) {
                    return;
                }
                const product = {
                    id: productId,
                    name: productName,
                    quantity: quantity,
                    price: price,
                };

                products.push(product);

                $('#products').val(JSON.stringify(products));
                const newRow = `
            <tr>
                <td>${productName}</td>
                <td>${quantity}</td>
                <td>${price}</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm"
                            onclick="removeProduct(${products.length - 1})">
                        Remove
                    </button>
                </td>
            </tr>
        `;
                $('#productTable tbody').append(newRow);
                $('#productSelect').val('').trigger('change');
                $('#productQuantity').val('');
                $('#productPrice').val('');
                $('#productModal').modal('hide');
            };

            window.removeProduct = function (index) {
                products.splice(index, 1);

                $('#products').val(JSON.stringify(products));
                $('#productTable tbody').empty();
                products.forEach((product, idx) => {
                    const row = `
                <tr>
                    <td>${product.name}</td>
                    <td>${product.quantity}</td>
                    <td>${product.price}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm"
                                onclick="removeProduct(${idx})">
                            Remove
                        </button>
                    </td>
                </tr>
            `;
                    $('#productTable tbody').append(row);
                });
            };
        });
        const now = new Date();
        const formattedDate = now.toISOString().slice(0, 16);
        document.getElementById('date').value = formattedDate;

        function setAction(action) {
            document.getElementById('actionType').value = action;
        }
    </script>
@endsection
