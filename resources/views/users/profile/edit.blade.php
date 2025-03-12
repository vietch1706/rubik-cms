@php use App\Models\Users\Users;use Illuminate\Support\Facades\Session; @endphp
@extends('layout.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}" type="text/css">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-10 p-5">
                <form method="POST" action="{{ route('profile.update') }}"
                      enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col text-center">
                            <div class="profile-image-container mb-3">
                                <img src="{{ auth()->user()->avatar ?? asset('storage/avatars/default-avatar.png') }}"
                                     alt="Profile Picture"
                                     class="rounded-circle"
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                            <div class="mb-3 w-50 mx-auto">
                                <label for="avatar" class="form-label">Change Profile Picture</label>
                                <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">First Name <span class="required"> * </span></label>
                            <input type="text" class="form-control" name="first_name" value="{{ $user['first_name'] }}">
                            @error('first_name')
                            <span class="text-danger error">{{ $errors->first('first_name') }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Last Name <span class="required"> * </span></label>
                            <input type="text" class="form-control" name="last_name" value="{{ $user['last_name'] }}">
                            @error('last_name')
                            <span class="text-danger error">{{ $errors->first('last_name') }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gender <span class="required"> * </span></label>
                            <select class="form-select select2-overwrite" name="gender">
                                @foreach($genders as $key => $gender)
                                    <option value="{{$key}}" @if($key == $user['gender']) selected @endif>
                                        {{ $gender }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role<span class="required"> * </span></label>
                            <input type="text" class="form-control" value="{{ $user['role'] }}" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone <span class="required"> * </span></label>
                            <input type="text" class="form-control" name="phone" value="{{ $user['phone'] }}">
                            @error('phone')
                            <span class="text-danger error">{{ $errors->first('phone') }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email <span class="required"> * </span></label>
                            <input type="email" class="form-control" name="email" value="{{ $user['email'] }}" readonly>
                            @error('email')
                            <span class="text-danger error">{{ $errors->first('email') }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div @class(['mb-3', 'col' => $user['role'] == Users::ROLE_ADMIN, 'col-md-6' => $user['role'] == Users::ROLE_EMPLOYEE])>
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" value="{{ $user['address'] }}">
                            @error('address')
                            <span class="text-danger error">{{ $errors->first('address') }}</span>
                            @enderror
                        </div>
                        @if($user['role'] == Users::ROLE_EMPLOYEE)
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Salary <span class="note"> (Unit of salary is thousand)</span><span
                                        class="required"> * </span></label>
                                <input type="text" class="form-control" name="salary" value="{{ $user['salary'] }}"
                                       readonly>
                                @error('salary')
                                <span class="text-danger error">{{ $errors->first('salary') }}</span>
                                @enderror
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Is Activated</label>
                            <input class="form-control" type="text" name="is_activated"
                                   value="{{ $user['is_activated'] }}"
                                   readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Activated At</label>
                            <input class="form-control" type="datetime-local" name="activated_at" id="activatedAt"
                                   value="{{ $user['activated_at'] }}" readonly>
                            @error('activated_at')
                            <span class="text-danger error">{{ $errors->first('activated_at') }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Created At </label>
                            <input type="datetime-local" class="form-control" value="{{ $user['created_at'] }}"
                                   readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Updated At </label>
                            <input type="datetime-local" class="form-control" value="{{ $user['updated_at'] }}"
                                   readonly>
                        </div>
                    </div>
                    <div class="row mx-5 justify-content-center">
                        <button type="submit" class="btn btn-primary me-2 w-50">Update</button>
                    </div>
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

        document.getElementById('avatar').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.querySelector('.profile-image-container img').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
