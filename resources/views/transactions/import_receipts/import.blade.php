@php
    use Illuminate\Support\Facades\Session;
@endphp
@extends('layout.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}" type="text/css">
    <style>
        .card {
            background-color: var(--latte-base);
            border: 1px solid var(--latte-subtle);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px); /* Lift on hover */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background-color: var(--latte-header);
            color: var(--latte-dark);
            font-weight: bold;
            padding: 15px;
            font-size: 18px;
            border-bottom: 1px solid var(--latte-subtle);
        }

        .card-body {
            background-color: var(--latte-light);
            color: var(--latte-text);
            padding: 20px;
            border-radius: 0 0 10px 10px;
        }

        .card-body.custom-card-body {
            background-color: var(--latte-light);
            animation: fadeIn 0.5s ease-in-out;
        }

        .card-body form .form-label {
            color: var(--latte-text);
            font-weight: 500;
        }

        .card-body form .form-control {
            background-color: var(--latte-input-bg);
            color: var(--latte-input-text);
            border: 1px solid var(--latte-subtle);
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .card-body form .form-control:focus {
            background-color: var(--latte-light);
            border-color: var(--latte-primary);
            box-shadow: 0 0 6px rgba(140, 170, 238, 0.5);
        }

        .card-body button.btn-primary {
            background-color: var(--latte-primary);
            border: none;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .card-body button.btn-primary:hover {
            background-color: var(--latte-secondary);
            box-shadow: 0 4px 8px rgba(166, 218, 149, 0.3);
            transform: scale(1.05);
        }

        .card-body .link-secondary {
            color: var(--latte-muted);
            text-decoration: underline;
            transition: color 0.3s ease;
        }

        .card-body .link-secondary:hover {
            color: var(--latte-highlight);
        }

        /* Add animation for smooth fade-in */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

    </style>
    <div class="container-fluid m-0">
        <div class="card bg-light mt-3">
            <div class="card-header">
                Import Data
            </div>
            <div class="card-body">
                <form action="{{ route('import.preview') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <label class="form-label">Order Number <span class="required"> * </span></label>
                            <select class="form-control select2-overwrite" name="order_number">
                                <option value="">Select Order Number</option>
                                @foreach($orders as $key => $order)
                                    <option @if(old('order_number')) value="{{ old('order_number') }}"
                                            @else  value="{{$order}}" @endif>
                                        {{ $order }}
                                    </option>
                                @endforeach
                            </select>
                            @error('order_number')
                            <span class="text-danger error">{{ $errors->first('order_number') }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <label for="csv_file" class="form-label">Choose CSV file <span
                                    class="required"> * </span></label>
                            <input type="file" class="form-control" name="csv_file" accept=".csv">
                        </div>
                        @error('csv_file')
                        <span class="text-danger error">{{ $errors->first('csv_file') }}</span>
                        @enderror
                    </div>

                    <div class="row mt-3">
                        <div class="col">
                            <a href="{{ asset('storage/sample-data.csv') }}" class="link-secondary download-link"
                               download target="_blank">
                                Download Sample Data
                            </a>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary me-3">Import</button>
                    <span>Or</span>&nbsp;
                    <a href="{{ route('receipts') }}"
                       class="link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">Cancel</a>
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
    </script>
@endsection
