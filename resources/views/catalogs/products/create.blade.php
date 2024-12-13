@php use Illuminate\Support\Facades\Session; @endphp
@extends('layout.app')
@section('content')
    <style>
        /* General Input Styling */
        input, select {
            font-size: 16px;
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            outline: none;
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        input:focus, select:focus {
            border-color: #80bdff;
            box-shadow: 0 0 5px rgba(128, 189, 255, 0.5);
        }

        .select2-container .select2-selection--single {
            height: calc(2.25rem + 2px); /* Matches input height */
            padding: 0.375rem 0.75rem;
        }

        .select2-selection__rendered {
            line-height: 1.5;
            padding-left: 5px;
        }

        /* Hide Number Input Arrows */
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

        /* Labels */
        .form-label {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 5px;
            display: block;
        }

        /* Required Field Indicator */
        .required {
            font-size: 18px;
            color: #e22b30;
            margin-left: 5px;
            vertical-align: middle;
        }

        /* Error Messages */
        .error {
            font-size: 14px;
            color: #e22b30;
            margin-top: 5px;
            display: block;
        }

        /* Buttons */
        button {
            font-size: 16px;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            transition: background-color 0.2s ease-in-out;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: #fff;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .link-secondary {
            font-size: 14px;
            color: #6c757d;
            text-decoration: none;
        }

        .link-secondary:hover {
            color: #343a40;
            text-decoration: underline;
        }

        /* Form Layout */
        .row {
            margin-bottom: 20px;
        }

        .col-md-6 {
            margin-bottom: 15px;
        }

        .mb-3 {
            margin-bottom: 1rem !important;
        }

        /* Modal Styling */
        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            font-size: 18px;
            font-weight: bold;
        }

        .modal-footer button {
            margin-left: 10px;
        }

        /* Alert */
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
            padding: 10px 15px;
            border-radius: 4px;
        }
    </style>

    <form method="POST" action="{{ route('brands.store') }}" enctype="multipart/form-data">
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
                <input type="text" class="form-control" id="slug" name="slug" readonly
                       value="{{ old('slug') }}">
                @error('slug')
                <span class="text-danger error">{{ $errors->first('slug') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Category</label>
                <select class="form-select" name="category_id" id="category_box">
                    <option value="">Select Category</option>
                    @foreach($categories as $key => $category)
                        <option value="{{$key}}" @if($key == old('category_id')) selected @endif>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                <span class="text-danger error">{{ $errors->first('category_id') }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Brand</label>
                <select class="form-select" name="brand_id" id="brand_box">
                    <option value="">Select Brand</option>
                    @foreach($brands as $key => $brand)
                        <option value="{{$key}}" @if($key == old('brand_id')) selected @endif>
                            {{ $brand }}
                        </option>
                    @endforeach
                </select>
                @error('brand_id')
                <span class="text-danger error">{{ $errors->first('brand_id') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Image</label>
                <input class="form-control" type="file" accept="image/png, image/jpeg, image/jpg" name="image">
                @error('image')
                <span class="text-danger error">{{ $errors->first('image') }}</span>
                @enderror
            </div>
        </div>

        <input type="hidden" name="action" id="actionType" value="save">
        <button type="submit" class="btn btn-primary me-3">Save</button>
        <button type="submit" class="btn btn-secondary me-3" onclick="setAction('save_and_close')">Save and Close
        </button>
        <span>Or</span>
        <a type="submit" class="link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
           href="{{ route('brands') }}">Cancel</a>
    </form>

    <script src="{{ asset('/js/jquery.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
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

            $('#category_box').select2({
                placeholder: "Select Category",
                allowClear: true
            });
            $('#brand_box').select2({
                placeholder: "Select Brand",
                allowClear: true
            });

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
