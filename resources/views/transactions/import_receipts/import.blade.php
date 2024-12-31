@php use Illuminate\Support\Facades\Session; @endphp
@extends('layout.app')
@section('content')
    <style>
        .container-fluid {
            padding: 1rem;
        }

        .card {
            background-color: var(--latte-base);
            border: 1px solid var(--latte-subtle);
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: var(--latte-header);
            color: var(--latte-text);
            font-weight: bold;
            padding: 1rem;
            border-bottom: 1px solid var(--latte-subtle);
        }

        .card-body {
            padding: 1rem;
        }

        .form-control {
            border: 1px solid var(--latte-subtle);
            background-color: var(--latte-input-bg);
            color: var(--latte-input-text);
            border-radius: 0.25rem;
            padding: 0.5rem;
            margin-bottom: 1rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 0.25rem;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-primary {
            background-color: var(--latte-primary);
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: #7BA4E2;
            transform: scale(1.05);
        }

        .btn-secondary {
            background-color: var(--latte-secondary);
            color: #ffffff;
        }

        .btn-secondary:hover {
            background-color: #96CF85;
            transform: scale(1.05);
        }

        a.link-secondary {
            color: var(--latte-cancel);
            text-decoration: none;
            font-weight: bold;
        }

        a.link-secondary:hover {
            text-decoration: underline;
        }
    </style>
    <div class="container-fluid m-0">
        <div class="card bg-light mt-3">
            <div class="card-header">
                Import Data
            </div>
            <div class="card-body">
                <form action="{{ route('receipts.import_preview') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <label class="form-label">Order Number <span class="required"> * </span></label>
                            <select class="form-control select2" name="order_number">
                                <option value="">Select Order Number</option>
                                @foreach($orders as $key => $order)
                                    <option value="{{$order}}">
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
                            <label for="csv_file" class="form-label">Choose CSV file <span class="required">
                                * </span></label>
                            <input type="file" class="form-control" name="csv_file" accept=".csv">
                        </div>
                        @error('csv_file')
                        <span class="text-danger error">{{ $errors->first('csv_file') }}</span>
                        @enderror
                    </div>
                    <input type="hidden" name="action" id="actionType" value="import">
                    <button type="submit" class="btn btn-primary me-3">Import</button>
                    <button type="submit" class="btn btn-secondary me-3" onclick="setAction('import_and_close')">
                        Import and Close
                    </button>
                    <span>Or</span>&nbsp;
                    <a href="{{ route('receipts') }}" class="link-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('/js/jquery-3.7.1.min.js') }}"></script>
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

        $(document).ready(function () {
            $('.select2').select2({
                placeholder: "Select Order Number",
                allowClear: true,
                theme: 'bootstrap-5',
                width: '100%'
            });
        });
    </script>
@endsection
