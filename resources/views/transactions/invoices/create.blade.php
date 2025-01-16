@php use App\Models\Transactions\Orders;use Illuminate\Support\Facades\Session; @endphp
@extends('layout.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}" type="text/css">
    <form method="POST" action="{{ route('invoices.store') }}" enctype="multipart/form-data" id="orderForm">
        @csrf
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Employee <span class="required"> * </span></label>
                <input type="text" class="form-control" value="{{ $current_employee['full_name'] }}" readonly>
                <input type="hidden" name="employee_id" value="{{ $current_employee['id'] }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Date <span class="required"> * </span></label>
                <input class="form-control" type="datetime-local" name="date" id="date" readonly>
            </div>
            <div class="col-md-4 mb-3 ">
                <label class="form-label">Status</label>
                <input class="form-control" type="text"
                       value="{{ $statuses[Orders::STATUS_PENDING] }}" readonly>
                <input class="form-control" type="hidden" name="status" value="{{ Orders::STATUS_PENDING }}" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Customer</label>
                <select class="form-control select2-overwrite" name="customer_id">
                    <option value="">Select Customer</option>
                    @foreach($customers as $key => $customer)
                        <option value="{{$key}}">
                            {{ $customer }}
                        </option>
                    @endforeach
                </select>
                @error('customer_id')
                <span class="text-danger error">{{ $errors->first('customer_id') }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Campaign</label>
                <select class="form-control select2-overwrite" name="campaign_id">
                    <option value="">Select Campaign</option>
                    @foreach($campaigns as $key => $campaign)
                        <option value="{{$key}}">
                            {{ $campaign }}
                        </option>
                    @endforeach
                </select>
                @error('customer_id')
                <span class="text-danger error">{{ $errors->first('customer_id') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Note</label>
                <input class="richeditor" name="note" id="note" value="{{ old('note') }}">
                @error('note')
                <div class="text-danger error">{{ $errors->first('note') }}</div>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
                <div class="d-flex justify-content-start align-items-center mb-2">
                    <label class="form-label me-5">Products<span class="required"> * </span></label>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#productModal">
                        Add Product
                    </button>
                </div>
                <br>
                <span id="validationError" class="text-danger error" style="display: none;">
                     Please select distributor before adding products.
                </span>
                @error('products')
                <span class="text-danger error">{{ $errors->first('products') }}</span>
                @enderror
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
                    <tfoot style="display: none">
                    <tr>
                        <td colspan="2" class="text-end fw-bold">Total Price:</td>
                        <td id="totalPriceCell" class="fw-bold">0.00</td>
                        <td></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <input type="hidden" name="action" id="actionType" value="save">
        <button type="submit" class="btn btn-primary me-3">Save</button>
        <button type="submit" class="btn btn-secondary me-3" onclick="setAction('save_and_close')">
            Save and Close
        </button>
        <span>Or</span>
        <a type="submit" class="link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
           href="{{ route('invoices') }}">Cancel</a>
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
                            <select class="form-control " id="productSelect">
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product['id'] }}" data-name="{{ $product['name'] }}"
                                            data-price="{{ $product['price'] }}">
                                        {{ $product['name'] }} - {{ $product['sku'] }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="text-danger error" id="productSelect-error"></span>
                            <input type="hidden" name="products" id="products">
                        </div>
                        <div class="mb-3">
                            <label for="productPrice" class="form-label">Price <span class="required"> * </span></label>
                            <input type="number" class="form-control" id="productPrice" readonly>
                            <span class="text-danger error" id="productPrice-error"></span>
                        </div>
                        <div class="mb-3">
                            <label for="productQuantity" class="form-label">Quantity <span
                                    class="required"> * </span></label>
                            <input type="number" class="form-control" id="productQuantity">
                            <span class="text-danger error" id="productQuantity-error"></span>
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
    <script src="{{ asset('js/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>

    <script>
        @if (Session::has('success'))
        Swal.fire(
            '{{ Session::get('success') }}',
            '',
            'success'
        );
        @endif

        $(document).ready(function () {
            const chosenProducts = [];
            let products = [];
            let totalPrice = 0;

            function updateTotalPrice() {
                if (products.length > 0) {
                    $('#productTable tfoot').show();
                } else {
                    $('#productTable tfoot').hide();
                }
                totalPrice = products.reduce((sum, product) => sum + product.quantity * product.price, 0);
                $('#totalPriceCell').text(totalPrice.toFixed(2));
            }

            $('#productSelect').select2({
                placeholder: "Search and select an option",
                allowClear: true,
                theme: 'bootstrap-5',
                width: '100%',
                dropdownParent: $('#productModal')
            });
            $('#productSelect').change(function () {
                const selectedOption = $(this).find('option:selected');
                const price = selectedOption.data('price') || '';
                $('#productPrice').val('');
                $('#productPrice').val(price);
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

                if (chosenProducts.includes(productId)) {
                    $('#productSelect-error').text('This product is already added');
                    hasError = true;
                }

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
                chosenProducts.push(productId);
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
                updateTotalPrice();
                $('#productSelect').val('').trigger('change');
                $('#productQuantity').val('');
                $('#productPrice').val('');
                $('#productModal').modal('hide');
            };

            window.removeProduct = function (index) {
                products.splice(index, 1);
                chosenProducts.splice(index, 1);
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
                updateTotalPrice();
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
