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
            height: calc(100% - 88px);
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

        .form-control {
            background-color: var(--latte-input-bg);
            color: var(--latte-input-text);
            border: 1px solid var(--latte-muted);
        }

        .form-control:focus {
            border-color: var(--latte-primary);
            box-shadow: 0 0 0 0.25rem rgba(140, 170, 238, 0.25);
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
                                class="form-control form-control-lg"
                                placeholder="Enter a valid email address"
                                name="email"
                                required
                                autofocus/>
                        </div>

                        <!-- Password input -->
                        <div data-mdb-input-init class="form-outline mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input
                                type="password"
                                name="password"
                                class="form-control form-control-lg"
                                placeholder="Enter password"
                                required/>
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
                Copyright © 2024. All rights reserved.
            </div>
            <!-- Copyright -->
        </div>
    </section>
@endsection
