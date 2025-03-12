@php use Carbon\Carbon; @endphp
@extends('layout.app')
@section('content')
    <style>
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: var(--latte-base);
            font-family: Arial, sans-serif;
            color: var(--latte-text);
        }

        .divider:after,
        .divider:before {
            content: "";
            flex: 1;
            height: 1px;
            background: var(--latte-subtle);
        }

        .h-custom {
            height: calc(100% - 72px);
        }


        .container-fluid {
            background-color: var(--latte-header);
        }

        .left-item {
            background: linear-gradient(135deg, var(--latte-primary), var(--latte-secondary));
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .left-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
        }

        .left-item img {
            width: 100%;
            height: auto;
            border-radius: 15px;
            transition: transform 0.3s ease;
        }

        .left-item img:hover {
            transform: scale(1.05);
        }

        .right-item {
            background-color: var(--latte-light);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .right-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
        }

        .form-label {
            color: var(--latte-text);
        }

        .form-label {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 5px;
            display: block;
            color: var(--latte-text);
            transition: color 0.2s ease-in-out;
        }

        .form-control {
            font-size: 16px;
            padding: 8px 8px 8px 20px;
            border: 1px solid var(--latte-subtle);
            outline: none;
            background-color: var(--latte-input-bg);
            color: var(--latte-input-text);
            border-radius: 35px;
            transition: border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out, transform 0.2s ease-in-out;
        }

        .form-control:focus {
            border-color: var(--latte-primary);
            box-shadow: 0 0 8px rgba(140, 170, 238, 0.5);
            transform: scale(1.02);
            outline: none;
        }

        .btn-primary {
            background-color: var(--latte-primary);
            border-color: var(--latte-primary);
        }

        .btn-primary:hover {
            background-color: var(--latte-secondary);
            border-color: var(--latte-secondary);
        }

        .text-body {
            color: var(--latte-primary);
        }

        .text-body:hover {
            color: var(--latte-secondary);
        }

        .link-danger {
            color: var(--latte-secondary);
        }

        .link-danger:hover {
            color: var(--latte-primary);
        }

        @media (max-width: 450px) {
            .h-custom {
                height: 100%;
            }
        }

        @media (max-width: 800px) {
            .left-item {
                display: none;
            }
        }
    </style>
    <section class="vh-100">
        <div class="container-fluid h-custom">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="left-item col-md-5 col-lg-5 col-xl-4">
                    <img src="{{ asset('img/image.png') }}" alt="">
                </div>
                <div class="right-item col-md-5 col-lg-5 col-xl-4 offset-xl-1">
                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf
                        <div class="divider d-flex align-items-center my-4">
                            <h2 class="text-center fw-bold mx-3 mb-0">Admin Login Channel</h2>
                        </div>
                        <div data-mdb-input-init class="form-outline mb-4">
                            <label for="email" class="form-label">Email address</label>
                            <input
                                type="email"
                                class="form-control"
                                placeholder="Enter a valid email address"
                                name="email"/>
                            @error('email')
                            <span class="text-danger error">{{ $errors->first('email') }}</span>
                            @enderror
                        </div>
                        <div data-mdb-input-init class="form-outline mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group password-container">
                                <input type="password" class="form-control password-field"
                                       name="password"
                                       placeholder="Enter password">
                                <span class="input-group-text">
                            <i class="fa-solid fa-eye-slash toggle-password"></i>
                        </span>
                            </div>
                            @error('password')
                            <span class="text-danger error">{{ $errors->first('password') }}</span>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="" class="text-body">Forgot password?</a>
                        </div>
                        <div class="divider d-flex align-items-center my-4">
                            <p class="text-center fw-bold mx-3 mb-0">Or</p>
                        </div>
                        <div class="text-center mx-auto">
                            <button type="submit"
                                    class="btn btn-primary btn-lg"
                                    style="width: 100%"
                            >Login
                            </button>
                            <p class="small fw-bold mt-2 pt-1 mb-0">Go to the
                                <a href="" class="link-danger">Main Website!</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div
            class="d-flex flex-column flex-md-row text-center text-md-start justify-content-between py-4 px-4 px-xl-5 bg-primary">
            <!-- Copyright -->
            <div class="text-white mb-3 mb-md-0">
                Copyright © {{Carbon::now()->format('Y')}}. All rights reserved.
            </div>
            <!-- Copyright -->
        </div>
    </section>
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
        @if (Session::has('error'))
        Swal.fire({
            position: "top-end",
            icon: "error",
            title: '{{ Session::get('error') }}',
            showConfirmButton: false,
            timer: 1000
        });
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
