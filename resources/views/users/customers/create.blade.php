@php use Illuminate\Support\Facades\Session; @endphp
@extends('layout.app')
@section('content')
    <form method="POST" action="{{ route('customers.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3 ">
                <label class="form-label">First Name <span class="required"> * </span></label>
                <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}">
                @error('first_name')
                <span class="text-danger error">{{ $errors->first('first_name') }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Last Name <span class="required"> * </span></label>
                <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}">
                @error('last_name')
                <span class="text-danger error">{{ $errors->first('last_name') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Type <span class="required"> * </span></label>
                <select class="form-select" name="type">
                    @foreach($types as $key => $type)
                        <option value="{{$key}}" @if($key == old('type')) selected @endif>
                            {{$type}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3 ">
                <label class="form-label">Gender <span class="required"> * </span></label>
                <select class="form-select" name="gender">
                    @foreach($genders as $key => $gender)
                        <option value="{{$key}}" @if($key == old('gender')) selected @endif>
                            {{ $gender }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3 ">
                <label class="form-label">Phone <span class="required"> * </span></label>
                <input type="number" class="form-control" name="phone" value="{{ old('phone') }}">
                @error('phone')
                <span class="text-danger error">{{ $errors->first('phone') }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Email <span class="required"> * </span></label>
                <input type="email" class="form-control" name="email" value="{{ old('email') }}">
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
                <label class="form-label">Identity Number<span class="required"> * </span> </label>
                <input type="number" class="form-control" name="identity_number" value=" {{ old('identity_number') }}">
                @error('identity_number')
                <span class="text-danger error">{{ $errors->first('identity_number') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Address</label>
                <input type="text" class="form-control" name="address" value="{{ old('address') }}">
                @error('address')
                <span class="text-danger error">{{ $errors->first('address') }}</span>
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
            <div class="col-md-6 mb-3 ">
                <label class="form-label">Is Activated</label>
                <select class="form-select" name="is_activated" id="isActivated">
                    @foreach($isActivateds as $key => $isActivated)
                        <option value="{{$key}}">
                            {{ $isActivated }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Activated At</label>
                <input class="form-control" type="datetime-local" name="activated_at" id="activatedAt"
                       value="{{ old('activated_at') }}">
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
           href="{{ route('customers') }}">Cancel</a>
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
