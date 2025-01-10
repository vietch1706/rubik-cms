@php use Illuminate\Support\Facades\Session; @endphp
@extends('layout.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}" type="text/css">
    <form method="POST" action="{{ route('campaigns.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3 ">
                <label class="form-label">Name <span class="required"> * </span></label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                @error('name')
                <span class="text-danger error">{{ $errors->first('name') }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Slug <span class="required"> * </span></label>
                <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug') }}">
                @error('slug')
                <span class="text-danger error">{{ $errors->first('slug') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Start Date </label>
                <input type="datetime-local" class="form-control" value="{{ old('start_date') }}" name="start_date">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">End Date </label>
                <input type="datetime-local" class="form-control" value="{{ old('end_date') }}" name="end_date">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Add Details <span class="required"> * </span></label>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#productModal">
                    Add
                </button>
                @error('products')
                <span class="text-danger error">{{ $errors->first('products') }}</span>
                @enderror
            </div>
        </div>
        <input type="hidden" name="action" id="actionType" value="save">
        <button type="submit" class="btn btn-primary me-3">Save</button>
        <button type="submit" class="btn btn-secondary me-3" onclick="setAction('save_and_close')">Save and Close
        </button>
        <span>Or</span>
        <a type="submit" class="link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
           href="{{ route('campaigns') }}">Cancel</a>
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
        <div class="row mt-4">
            <div class="col-md-12 mb-3">
                <label class="form-label">Products</label>
                <table class="table table-bordered table-striped" id="productTable">
                    <thead>
                    <tr>
                        <th>Product</th>
                        <th>Type</th>
                        <th>Discount Percent</th>
                        <th>Bundle Product</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
    <script src="{{ asset('js/sweetalert2@11.js') }}"></script>
    <script>
        @if (Session::has('success'))
        Swal.fire(
            '{{ Session::get('success') }}',
            '',
            'success'
        );
        @endif

        function generateSlug(name) {
            return name
                .toLowerCase()
                .replace(/\s+/g, '-')
        }

        document.addEventListener('DOMContentLoaded', function () {
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');

            nameInput.addEventListener('input', function () {
                slugInput.value = generateSlug(nameInput.value);
            });
        });


        function setAction(action) {
            document.getElementById('actionType').value = action;
        }
    </script>
@endsection
