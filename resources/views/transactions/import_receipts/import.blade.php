@php
    use Illuminate\Support\Facades\Session;
@endphp

@extends('layout.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}" type="text/css">

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
                            <select class="form-control select2" name="order_number">
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
