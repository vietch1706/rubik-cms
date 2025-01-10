@php use Illuminate\Support\Facades\Session; @endphp
@extends('layout.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}" type="text/css">
    <form method="POST" action="{{ route('distributors.update', ['id' => $distributor['id']]) }}"
          enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="row">
            <div class="col-md-6 mb-3 ">
                <label class="form-label">Name <span class="required"> * </span></label>
                <input type="text" class="form-control" name="name" value="{{ $distributor['name'] }}">
                @error('name')
                <span class="text-danger error">{{ $errors->first('name') }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Country <span class="required"> * </span></label>
                <input type="text" class="form-control" name="country" value="{{ $distributor['country'] }}">
                @error('country')
                <span class="text-danger error">{{ $errors->first('country') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Address <span class="required"> * </span></label>
                <input type="text" class="form-control" name="address" value="{{ $distributor['address'] }}">
                @error('address')
                <span class="text-danger error">{{ $errors->first('address') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3 ">
                <label class="form-label">Phone <span class="required"> * </span></label>
                <input type="text" class="form-control" name="phone" value="{{ $distributor['phone'] }}">
                @error('phone')
                <span class="text-danger error">{{ $errors->first('phone') }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Email <span class="required"> * </span></label>
                <input type="text" class="form-control" name="email" value="{{ $distributor['email'] }}">
                @error('email')
                <span class="text-danger error">{{ $errors->first('email') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Created At </label>
                <input type="datetime-local" class="form-control" value="{{ $distributor['created_at'] }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Updated At </label>
                <input type="datetime-local" class="form-control" value="{{ $distributor['updated_at'] }}" readonly>
            </div>
        </div>
        <input type="hidden" name="action" id="actionType" value="save">
        <div class="d-grid gap-2 d-md-flex justify-content-md-between">
            <div class="left-item">
                <button type="submit" class="btn btn-primary me-3">Save</button>
                <button type="submit" class="btn btn-secondary me-3" onclick="setAction('save_and_close')">Save and
                    Close
                </button>
                <span>Or</span>
                <a type="submit"
                   class="link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
                   href="{{ route('distributors') }}">Cancel</a>
            </div>
            <div class="right-item">
                <button class="delete-item" data-id="{{ $distributor['id'] }}">
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
                            url: "{{ route('distributors.destroy') }}",
                            type: "DELETE",
                            data: {
                                ids: selectedIds,
                                _token: "{{ csrf_token() }}",
                            },
                            success: function (response) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Your selected distributor have been deleted.',
                                    icon: 'success'
                                }).then(function () {
                                    window.location.href = "{{ route('distributors') }}";
                                });
                            },
                            error: function (response) {
                                console.log(response);
                                Swal.fire(
                                    'Error!',
                                    'There was a problem deleting the distributor.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });

        function setAction(action) {
            document.getElementById('actionType').value = action;
        }
    </script>
@endsection
