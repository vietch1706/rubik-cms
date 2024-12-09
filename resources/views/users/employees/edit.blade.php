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

    <form method="POST" action="{{ route('employees.update', ['id' => $employees['id']]) }}"
          enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="row">
            <div class="col-md-6 mb-3 ">
                <label class="form-label">First Name <span class="required"> * </span></label>
                <input type="text" class="form-control" name="first_name" value="{{ $employees['first_name'] }}">
                @error('first_name')
                <span class="text-danger error">{{ $errors->first('first_name') }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Last Name <span class="required"> * </span></label>
                <input type="text" class="form-control" name="last_name" value="{{ $employees['last_name'] }}">
                @error('last_name')
                <span class="text-danger error">{{ $errors->first('last_name') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3 ">
                <label class="form-label">Gender <span class="required"> * </span></label>
                <select class="form-select" name="gender">
                    @foreach($genders as $key => $gender)
                        <option value="{{$key}}" @if($key == $employees['gender']) selected @endif>
                            {{ $gender }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3 ">
                <label class="form-label">Salary <span class="required"> * </span></label>
                <input type="text" class="form-control" name="salary" value="{{ $employees['salary'] }}">
                @error('salary')
                <span class="text-danger error">{{ $errors->first('salary') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3 ">
                <label class="form-label">Phone <span class="required"> * </span></label>
                <input type="text" class="form-control" name="phone" value="{{ $employees['phone'] }}">
                @error('phone')
                <span class="text-danger error">{{ $errors->first('phone') }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Email <span class="required"> * </span></label>
                <input type="email" class="form-control" name="email" value="{{ $employees['email'] }}">
                @error('email')
                <span class="text-danger error">{{ $errors->first('email') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Password <span class="required"> * </span> </label>
                <input type="password" class="form-control" name="password">
                <span @if($errors->has('password')) class=" text-danger"@endif
                          > Your password must be at least 8 characters long and contain a mix of letters, numbers, and symbols.</span>
                @error('password')
                <span class="text-danger error">{{ $errors->first('password') }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Avatar</label>
                <input class="form-control" type="file" accept="image/png, image/jpeg, image/jpg" name="avatar">
                @error('avatar')
                <span class="text-danger error">{{ $errors->first('avatar') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Address</label>
                <input type="text" class="form-control" name="address" value="{{ $employees['address'] }}">
                @error('address')
                <span class="text-danger error">{{ $errors->first('address') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3 ">
                <label class="form-label">Is Activated</label>
                <select class="form-select" name="is_activated" id="isActivated">
                    @foreach($isActivateds as $key => $isActivated)
                        <option value="{{$key}}" @if($key == $employees['is_activated']) selected @endif>
                            {{ $isActivated }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Activated At</label>
                <input class="form-control" type="datetime-local" name="activated_at" id="activatedAt"
                       value="{{ $employees['activated_at'] }}">
                @error('activated_at')
                <span class="text-danger error">{{ $errors->first('activated_at') }}</span>
                @enderror
            </div>
        </div>
        <input type="hidden" name="action" id="actionType" value="save">
        <button type="submit" class="btn btn-primary me-3">Save</button>
        <button type="submit" class="btn btn-secondary me-3" onclick="setAction('save_and_close')">Save and Close
        </button>
        <span>Or</span>
        <a type="submit" class="link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
           href="{{ route('employees') }}">Cancel</a>
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

        const now = new Date();
        // Format the date to match the 'datetime-local' input format
        const formattedDate = now.toISOString().slice(0, 16);
        // Set the default value
        document.getElementById('activatedAt').value = formattedDate;
    </script>
@endsection
