@php use Illuminate\Support\Facades\Session; @endphp
@extends('layout.app')
@section('content')
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
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Created At </label>
                <input type="datetime-local" class="form-control" value="{{ $distributors['created_at'] }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Updated At </label>
                <input type="datetime-local" class="form-control" value="{{ $distributors['updated_at'] }}" readonly>
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

    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
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
