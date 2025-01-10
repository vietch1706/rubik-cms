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

        @media (max-width: 450px) {
            .h-custom {
                height: 100%;
            }
        }

        .container-fluid {
            background-color: var(--latte-header);
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

        .alert {
            background-color: var(--latte-cancel);
            color: var(--latte-text);
            border-radius: 5px;
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

        .footer {
            background-color: var(--latte-primary);
            color: white;
        }

        .footer a {
            color: white;
        }

        .footer a:hover {
            color: var(--latte-secondary);
        }
    </style>
    <section class="vh-100">
        <div class="container-fluid h-custom">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <img src="{{ asset('img/image.png') }}" alt="">
                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf
                        <div class="divider d-flex align-items-center my-4">
                            <h2 class="text-center fw-bold mx-3 mb-0">Admin Login Channel</h2>
                        </div>

                        @if($errors->any())
                            {!! implode('', $errors->all('<div class="alert alert-danger" role="alert"><h4 class="text-center alert-heading">:message</h4></div>')) !!}
                        @endif


                        <!-- Email input -->
                        <div data-mdb-input-init class="form-outline mb-4">
                            <label for="email" class="form-label">Email address</label>
                            <input
                                type="email"
                                class="form-control"
                                placeholder="Enter a valid email address"
                                name="email"/>
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
