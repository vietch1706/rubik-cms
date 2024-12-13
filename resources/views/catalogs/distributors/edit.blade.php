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

    <form method="POST" action="{{ route('distributors.update', ['id' => $distributors['id']]) }}"
          enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="row">
            <div class="col-md-6 mb-3 ">
                <label class="form-label">Name <span class="required"> * </span></label>
                <input type="text" class="form-control" name="name" value="{{ $distributors['name'] }}">
                @error('name')
                <span class="text-danger error">{{ $errors->first('name') }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Country <span class="required"> * </span></label>
                <input type="text" class="form-control" name="country" value="{{ $distributors['country'] }}">
                @error('country')
                <span class="text-danger error">{{ $errors->first('country') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Address <span class="required"> * </span></label>
                <input type="text" class="form-control" name="address" value="{{ $distributors['address'] }}">
                @error('address')
                <span class="text-danger error">{{ $errors->first('address') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3 ">
                <label class="form-label">Phone <span class="required"> * </span></label>
                <input type="text" class="form-control" name="phone" value="{{ $distributors['phone'] }}">
                @error('phone')
                <span class="text-danger error">{{ $errors->first('phone') }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Email <span class="required"> * </span></label>
                <input type="text" class="form-control" name="email" value="{{ $distributors['email'] }}">
                @error('email')
                <span class="text-danger error">{{ $errors->first('email') }}</span>
                @enderror
            </div>
        </div>
        <input type="hidden" name="action" id="actionType" value="save">
        <button type="submit" class="btn btn-primary me-3">Save</button>
        <button type="submit" class="btn btn-secondary me-3" onclick="setAction('save_and_close')">Save and Close
        </button>
        <span>Or</span>
        <a type="submit" class="link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
           href="{{ route('distributors') }}">Cancel</a>
    </form>

    <script>
        @if (Session::has('success'))
        Swal.fire(
            '{{ Session::get('success') }}',
            '',
            'success'
        );
        @endif
        function setAction(action) {
            document.getElementById('actionType').value = action;
        }
    </script>
@endsection
