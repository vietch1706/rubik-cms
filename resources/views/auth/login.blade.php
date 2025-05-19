@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app')

@section('content')
    <section class="min-h-screen flex items-center justify-center bg-latte-base">
        <div class="container mx-auto px-4 py-8">
            <div class="flex flex-col md:flex-row justify-center items-center gap-8 max-w-4xl mx-auto">
                <div class="hidden md:block w-full md:w-5/12">
                    <div
                        class="relative rounded-2xl overflow-hidden shadow-lg transform transition-transform hover:-translate-y-1 hover:shadow-xl">
                        <img src="{{ asset('img/image.png') }}" alt="Login Illustration" class="w-full h-auto object-cover">
                    </div>
                </div>

                <div
                    class="w-full md:w-5/12 bg-white rounded-2xl p-8 shadow-lg border border-latte-surface0 transform transition-transform hover:-translate-y-1 hover:shadow-xl">
                    <form method="POST" action="{{ route('admin.login.post') }}">
                        @csrf
                        <div class="text-center mb-6">
                            <h2 class="text-2xl font-bold text-latte-text">Admin Login Channel</h2>
                            <div class="mt-2 h-1 w-16 bg-latte-blue mx-auto"></div>
                        </div>

                        <div class="mb-6">
                            <label for="email" class="block text-sm font-medium text-latte-text mb-2">Email
                                Address</label>
                            <input type="email"
                                class="w-full px-4 py-3 rounded-full border border-latte-surface1 bg-latte-base text-latte-text placeholder-latte-subtext0 focus:outline-none focus:ring-2 focus:ring-latte-lavender focus:border-transparent transition-all duration-200"
                                placeholder="Enter a valid email address" name="email" id="email">
                            @error('email')
                                <span class="text-latte-red text-sm mt-2 block">{{ $errors->first('email') }}</span>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div class="mb-6">
                            <label for="password" class="block text-sm font-medium text-latte-text mb-2">Password</label>
                            <div class="relative">
                                <input type="password"
                                    class="password-field w-full px-4 py-3 rounded-full border border-latte-surface1 bg-latte-base text-latte-text placeholder-latte-subtext0 focus:outline-none focus:ring-2 focus:ring-latte-lavender focus:border-transparent transition-all duration-200"
                                    name="password" id="password" placeholder="Enter password">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-latte-subtext0 cursor-pointer">
                                    <i class="fa-solid fa-eye-slash toggle-password"></i>
                                </span>
                            </div>
                            @error('password')
                                <span class="text-latte-red text-sm mt-2 block">{{ $errors->first('password') }}</span>
                            @enderror
                        </div>

                        <!-- Forgot Password -->
                        <div class="flex justify-between items-center mb-6">
                            <a href="#"
                                class="text-latte-blue text-sm hover:text-latte-sapphire transition-colors duration-200">Forgot
                                password?</a>
                        </div>

                        <!-- Divider -->
                        <div class="flex items-center justify-center my-6">
                            <span class="text-latte-subtext0 text-sm px-4">Or</span>
                        </div>

                        <!-- Submit Button and Main Website Link -->
                        <div class="text-center">
                            <button type="submit"
                                class="w-full bg-latte-blue text-white py-3 rounded-full font-medium hover:bg-latte-sapphire focus:outline-none focus:ring-2 focus:ring-latte-lavender transition-all duration-200">
                                Login
                            </button>
                            <p class="mt-4 text-sm text-latte-text">
                                Go to the
                                <a href="#"
                                    class="text-latte-red hover:text-latte-maroon font-medium transition-colors duration-200">Main
                                    Website!</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 py-4 px-4 text-center text-latte-subtext0 bg-latte-mantle">
                <p>Copyright © {{ Carbon::now()->format('Y') }}. All rights reserved.</p>
            </div>
        </div>
    </section>

    <!-- Scripts -->
    <script src="{{ asset('js/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('/js/jquery-3.7.1.min.js') }}"></script>
    <script>
        @if (Session::has('success'))
            Swal.fire({
                title: '{{ Session::get('success') }}',
                icon: 'success',
                confirmButtonColor: '#1e66f5'
            });
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

        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.querySelector('.toggle-password');
            const passwordInput = document.querySelector('.password-field');

            toggleButton.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        });
    </script>
@endsection
