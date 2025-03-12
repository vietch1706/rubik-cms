@php use Illuminate\Support\Facades\Session; @endphp
@extends('layout.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}" type="text/css">
    <form method="POST" action="{{ route('employees.update', ['id' => $employee['id']]) }}"
          enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="row">
            <div class="col-md-6 mb-3 ">
                <label class="form-label">First Name <span class="required"> * </span></label>
                <input type="text" class="form-control" name="first_name" value="{{ $employee['first_name'] }}">
                @error('first_name')
                <span class="text-danger error">{{ $errors->first('first_name') }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Last Name <span class="required"> * </span></label>
                <input type="text" class="form-control" name="last_name" value="{{ $employee['last_name'] }}">
                @error('last_name')
                <span class="text-danger error">{{ $errors->first('last_name') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3 ">
                <label class="form-label">Gender <span class="required"> * </span></label>
                <select class="form-control select2-overwrite" name="gender">
                    @foreach($genders as $key => $gender)
                        <option value="{{$key}}" @if($key == $employee['gender']) selected @endif>
                            {{ $gender }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3 ">
                <label class="form-label">Salary <span class="note"> (Unit of salary is thousand)</span> <span
                        class="required"> * </span></label>
                <input type="text" class="form-control" name="salary" value="{{ $employee['salary'] }}">
                @error('salary')
                <span class="text-danger error">{{ $errors->first('salary') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3 ">
                <label class="form-label">Phone <span class="required"> * </span></label>
                <input type="text" class="form-control" name="phone" value="{{ $employee['phone'] }}">
                @error('phone')
                <span class="text-danger error">{{ $errors->first('phone') }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Email <span class="required"> * </span></label>
                <input type="email" class="form-control" name="email" value="{{ $employee['email'] }}" readonly>
                @error('email')
                <span class="text-danger error">{{ $errors->first('email') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Password <span class="required"> * </span> </label>
                <div class="input-group password-container">
                    <input type="password" class="form-control password-field" name="password">
                    <span class="input-group-text">
                            <i class="fa-solid fa-eye-slash toggle-password"></i>
                        </span>
                </div>
                <span @if($errors->has('password')) class=" text-danger"@endif
                          > Your password must be at least 8 characters long and contain a mix of letters, numbers, and symbols.</span>
                @error('password')
                <span class="text-danger error">{{ $errors->first('password') }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Avatar</label>
                <input class="form-control" type="file" accept="image/png, image/jpeg, image/jpg" name="avatar">
                @error('avatar')
                <span class="text-danger error">{{ $errors->first('avatar') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Address</label>
                <input type="text" class="form-control" name="address" value="{{ $employee['address'] }}">
                @error('address')
                <span class="text-danger error">{{ $errors->first('address') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3 ">
                <label class="form-label">Is Activated</label>
                <select class="form-control select2-overwrite" name="is_activated" id="isActivated">
                    @foreach($isActivateds as $key => $isActivated)
                        <option value="{{$key}}" @if($key == $employee['is_activated']) selected @endif>
                            {{ $isActivated }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Activated At</label>
                <input class="form-control" type="datetime-local" name="activated_at" id="activatedAt"
                       value="{{ $employee['activated_at'] }}">
                @error('activated_at')
                <span class="text-danger error">{{ $errors->first('activated_at') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Created At </label>
                <input type="datetime-local" class="form-control" value="{{ $employee['created_at'] }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Updated At </label>
                <input type="datetime-local" class="form-control" value="{{ $employee['updated_at'] }}" readonly>
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
                   href="{{ route('employees') }}">Cancel</a>
            </div>
            <div class="right-item">
                <button class="delete-item" data-id="{{ $employee['id'] }}">
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
            $('.select2-overwrite').select2({
                placeholder: "Search and select an option",
                allowClear: true,
                theme: 'bootstrap-5',
                width: '100%'
            });
        });
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
                            url: "{{ route('employees.destroy') }}",
                            type: "DELETE",
                            data: {
                                ids: selectedIds,
                                _token: "{{ csrf_token() }}",
                            },
                            success: function (response) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Your selected employees have been deleted.',
                                    icon: 'success'
                                }).then(function () {
                                    window.location.href = "{{ route('employees') }}";
                                });
                            },
                            error: function (response) {
                                console.log(response);
                                Swal.fire(
                                    'Error!',
                                    'There was a problem deleting the employees.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
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

        function setAction(action) {
            document.getElementById('actionType').value = action;
        }
    </script>
@endsection
