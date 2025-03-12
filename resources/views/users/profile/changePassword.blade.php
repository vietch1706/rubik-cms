@php use App\Models\Users\Users;use Illuminate\Support\Facades\Session; @endphp
@extends('layout.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}" type="text/css">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4 col-lg-7 p-5">
                <form method="POST" action="{{ route('profile.changePassword.update') }}">
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
                        <span class="pt-3
                              @if($errors->has('new_password') && $errors->first('new_password') == 'error') text-danger error @endif"
                        > Your password must be at least 8 characters long and contain a mix of letters, numbers, and symbols.</span>
                        @if($errors->first('new_password') != 'error')
                            <span class="text-danger error">{{ $errors->first('new_password') }}</span>
                        @endif
                    </div>
                    <div class="row">
                        <label class="form-label">Confirm Password <span class="required"> * </span> </label>
                        <div class="input-group password-container">
                            <input type="password" class="form-control password-field" name="confirm_password">
                            <span class="input-group-text">
                            <i class="fa-solid fa-eye-slash toggle-password"></i>
                        </span>
                        </div>
                        <span class="pt-3
                              @if($errors->has('confirm_password') && $errors->first('confirm_password') == 'error') text-danger error @endif"
                        > Your password must be at least 8 characters long and contain a mix of letters, numbers, and symbols.</span>
                        @if($errors->first('new_password') != 'error')
                            <span class="text-danger error">{{ $errors->first('confirm_password') }}</span>
                        @endif
                    </div>
                    <div class="row mx-5">
                        <button type="submit" class="btn btn-primary me-3">Change Password</button>
                    </div>
                </form>
            </div>
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
