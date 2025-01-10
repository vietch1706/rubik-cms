@php use App\Models\Users\Users;use Illuminate\Support\Facades\Session; @endphp
@extends('layout.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}" type="text/css">
    <div class="d-flex justify-content-around">
        <div class="col-md-5">
            <form method="POST" action="{{ route('profile.update', ['id' => $userId]) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="row position-relative start-50">
                    <div class="col text-center">
                        <div class="profile-image-container mb-3">
                            <img src="{{ auth()->user()->avatar ?? asset('storage/avatars/default-avatar.png') }}"
                                 alt="Profile Picture"
                                 class="rounded-circle"
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        </div>
                        <div class="mb-3 w-50 mx-auto">
                            <label for="avatar" class="form-label">Change Profile Picture</label>
                            <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3 ">
                        <label class="form-label">First Name <span class="required"> * </span></label>
                        <input type="text" class="form-control" name="first_name" value="{{ $user['first_name'] }}">
                        @error('first_name')
                        <span class="text-danger error">{{ $errors->first('first_name') }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Last Name <span class="required"> * </span></label>
                        <input type="text" class="form-control" name="last_name" value="{{ $user['last_name'] }}">
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
                                <option value="{{$key}}" @if($key == $user['gender']) selected @endif>
                                    {{ $gender }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3 ">
                        <label class="form-label">Role<span class="required"> * </span></label>
                        <input type="text" class="form-control" value="{{ $role }}" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3 ">
                        <label class="form-label">Phone <span class="required"> * </span></label>
                        <input type="text" class="form-control" name="phone" value="{{ $user['phone'] }}">
                        @error('phone')
                        <span class="text-danger error">{{ $errors->first('phone') }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email <span class="required"> * </span></label>
                        <input type="email" class="form-control" name="email" value="{{ $user['email'] }}" readonly>
                        @error('email')
                        <span class="text-danger error">{{ $errors->first('email') }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div @class(['mb-3', 'col' => $user['role_id'] == Users::ROLE_ADMIN, 'col-md-6' => $user['role_id'] == Users::ROLE_EMPLOYEE])>
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" name="address" value="{{ $user['address'] }}">
                        @error('address')
                        <span class="text-danger error">{{ $errors->first('address') }}</span>
                        @enderror
                    </div>
                    @if($user['role_id'] == Users::ROLE_EMPLOYEE)
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Salary <span
                                    class="note"> (Unit of salary is thousand)</span><span
                                    class="required"> * </span></label>
                            <input type="text" class="form-control" name="salary"
                                   value=" {{ $user['salary'] }}" readonly>
                            @error('salary')
                            <span class="text-danger error">{{ $errors->first('salary') }}</span>
                            @enderror
                        </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3 ">
                        <label class="form-label">Is Activated</label>
                        <select class="form-select" name="is_activated" id="isActivated">
                            @foreach($isActivateds as $key => $isActivated)
                                <option value="{{$key}}" @if($key == $user['is_activated']) selected @endif>
                                    {{ $isActivated }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Activated At</label>
                        <input class="form-control" type="datetime-local" name="activated_at" id="activatedAt"
                               value="{{ $user['activated_at'] }}">
                        @error('activated_at')
                        <span class="text-danger error">{{ $errors->first('activated_at') }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Created At </label>
                        <input type="datetime-local" class="form-control" value="{{ $user['created_at'] }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Updated At </label>
                        <input type="datetime-local" class="form-control" value="{{ $user['updated_at'] }}" readonly>
                    </div>
                </div>
                <div class="row mx-5">
                    <button type="submit" class="btn btn-primary me-3">Update</button>
                </div>
            </form>
        </div>
        <div class="col-md-5 my-auto">
            <form method="POST" action="{{ route('profile.change-password', ['id' => $userId]) }}">
                @csrf
                @method('put')
                <div class="row">
                    <label class="form-label">Password <span class="required"> * </span> </label>
                    <div class="input-group password-container">
                        <input type="password" class="form-control password-field" name="password">
                        <span class="input-group-text">
                            <i class="fa-solid fa-eye-slash toggle-password"></i>
                        </span>
                    </div>
                    <span @if($errors->has('password')) class=" text-danger"@endif
                    > Your password must be at least 8 characters long and contain a mix of letters, numbers, and
                    symbols.</span>
                    @error('password')
                    <span class="text-danger error">{{ $errors->first('password') }}</span>
                    @enderror
                </div>
                <div class="row">
                    <label class="form-label"> New Password <span class="required"> * </span> </label>
                    <div class="input-group password-container">
                        <input type="password" class="form-control password-field" name="new_password">
                        <span class="input-group-text">
                            <i class="fa-solid fa-eye-slash toggle-password"></i>
                        </span>
                    </div>
                    <span @if($errors->has('new_password')) class=" text-danger"@endif
                          > Your password must be at least 8 characters long and contain a mix of letters, numbers, and symbols.</span>
                    @error('new_password')
                    <span class="text-danger error">{{ $errors->first('new_password') }}</span>
                    @enderror
                </div>
                <div class="row">
                    <label class="form-label">Confirm Password <span class="required"> * </span> </label>
                    <div class="input-group password-container">
                        <input type="password" class="form-control password-field" name="confirm_password">
                        <span class="input-group-text">
                            <i class="fa-solid fa-eye-slash toggle-password"></i>
                        </span>
                    </div>
                    <span @if($errors->has('confirm_password')) class=" text-danger"@endif
                          > Your password must be at least 8 characters long and contain a mix of letters, numbers, and symbols.</span>
                    @error('confirm_password')
                    <span class="text-danger error">{{ $errors->first('confirm_password') }}</span>
                    @enderror
                </div>
                <div class="row mx-5">
                    <button type="submit" class="btn btn-primary me-3">Change Password</button>
                </div>
            </form>
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
        document.getElementById('avatar').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.querySelector('.profile-image-container img').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
        document.addEventListener('DOMContentLoaded', function () {
            const passwordContainers = document.querySelectorAll('.password-container');

            passwordContainers.forEach(container => {
                const toggleButton = container.querySelector('.toggle-password');
                const passwordInput = container.querySelector('.password-field');

                toggleButton.addEventListener('click', function () {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                });
            });
        });

    </script>
@endsection
