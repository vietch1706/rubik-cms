@extends('layout.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}" type="text/css">
    <form>
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">ID </label>
                <input type="text" class="form-control" value="{{ $id }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Environment </label>
                <input type="text" class="form-control" value="{{ $log['environment'] }}" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Message </label>
                <textarea type="text" class="form-control" readonly>{{ $log['message'] }}</textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Level </label>
                <input type="text" class="form-control" value="{{ $log['level'] }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Created At </label>
                <input type="datetime-local" class="form-control" value="{{ $log['timestamp'] }}" readonly>
            </div>
        </div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-between">
            <div class="left-item">
                <a href="{{ route('logs') }}" class="link-secondary">Return</a>
            </div>
            <div class="right-item">
                <button class="delete-item" data-id="{{ $id }}">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </div>
        </div>
    </form>
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
        Swal.fire(
            '{{ Session::get('error') }}',
            '',
            'error'
        );
        @endif
        $(document).ready(function () {
            $('.delete-item').on('click', function (event) {
                event.preventDefault();
                var selectedIds = [];
                var selectedId = $(this).data('id');
                selectedIds.push(selectedId);
                console.log(selectedIds);

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this action!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, keep it',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('receipts.destroy') }}",
                            type: "DELETE",
                            data: {
                                ids: selectedIds,
                                _token: "{{ csrf_token() }}",
                            },
                            success: function (response) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Your selected receipt have been deleted.',
                                    icon: 'success'
                                }).then(function () {
                                    window.location.href = "{{ route('receipts') }}";
                                });
                            },
                            error: function (response) {
                                console.log(response);
                                Swal.fire(
                                    'Error!',
                                    'There was a problem deleting the receipt.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
