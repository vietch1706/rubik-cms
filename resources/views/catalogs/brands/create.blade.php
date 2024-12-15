@php use Illuminate\Support\Facades\Session; @endphp
@extends('layout.app')
@section('content')
    <form method="POST" action="{{ route('brands.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col mb-3 ">
                <label class="form-label">Name <span class="required"> * </span></label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                @error('name')
                <span class="text-danger error">{{ $errors->first('name') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Slug <span class="required"> * </span></label>
                <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug') }}">
                @error('slug')
                <span class="text-danger error">{{ $errors->first('slug') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Image</label>
                <input class="form-control" type="file" accept="image/png, image/jpeg, image/jpg" name="image">
                @error('image')
                <span class="text-danger error">{{ $errors->first('image') }}</span>
                @enderror
            </div>
        </div>

        <input type="hidden" name="action" id="actionType" value="save">
        <button type="submit" class="btn btn-primary me-3">Save</button>
        <button type="submit" class="btn btn-secondary me-3" onclick="setAction('save_and_close')">Save and Close
        </button>
        <span>Or</span>
        <a type="submit" class="link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
           href="{{ route('brands') }}">Cancel</a>
    </form>

    <script>
        @if (Session::has('success'))
        Swal.fire(
            '{{ Session::get('success') }}',
            '',
            'success'
        );
        @endif

        function generateSlug(name) {
            return name
                .toLowerCase()
                .replace(/\s+/g, '-')
        }

        document.addEventListener('DOMContentLoaded', function () {
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');

            nameInput.addEventListener('input', function () {
                slugInput.value = generateSlug(nameInput.value);
            });
        });


        function setAction(action) {
            document.getElementById('actionType').value = action;
        }
    </script>
@endsection
