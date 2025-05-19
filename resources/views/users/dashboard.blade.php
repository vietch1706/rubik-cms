@extends('layouts.app')
@section('content')
    <div>dasd</div>
    <script src="{{ asset('js/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('/js/jquery-3.7.1.min.js') }}"></script>
    <script>
        @if (Session::has('success'))
            Swal.fire({
                icon: "success",
                title: "{{ Session::get('success') }}",
                showConfirmButton: false,
                timer: 1500
            });
        @endif
    </script>
@endsection
