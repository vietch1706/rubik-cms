@php use Illuminate\Support\Facades\Session; @endphp
@extends('layout.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}" type="text/css">
    <form method="POST" action="{{ route('categories.update', ['id' => $category['id']]) }}"
          enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="row">
            <div class="col-md-6 mb-3 ">
                <label class="form-label">Name <span class="required"> * </span></label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $category['name'] }}">
                @error('name')
                <span class="text-danger error">{{ $errors->first('name') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Slug <span class="required"> * </span></label>
                <input type="text" class="form-control" id="slug" name="slug" readonly
                       value="{{ $category['slug'] }}">
                @error('slug')
                <span class="text-danger error">{{ $errors->first('slug') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Parent Category</label>
                <select class="form-control select2-overwrite" name="parent_id">
                    <option value="" selected>Parent Category</option>
                    @foreach($parentCategories as $key => $parentCategory)
                        <option value="{{ $key }}"
                                @if($category['parent_category'] && $key == key($category['parent_category'])) selected @endif>
                            {{ $parentCategory }}
                        </option>
                    @endforeach
                </select>
                @error('parent_id')
                <span class="text-danger error">{{ $errors->first('parent_id') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Created At </label>
                <input type="datetime-local" class="form-control" value="{{ $category['created_at'] }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Updated At </label>
                <input type="datetime-local" class="form-control" value="{{ $category['updated_at'] }}" readonly>
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
                   href="{{ route('campaigns') }}">Cancel</a>
            </div>
            <div class="right-item">
                <button class="delete-item" data-id="{{ $campaigns['id'] }}">
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
                            url: "{{ route('campaigns.destroy') }}",
                            type: "DELETE",
                            data: {
                                ids: selectedIds,
                                _token: "{{ csrf_token() }}",
                            },
                            success: function (response) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Your selected campaign have been deleted.',
                                    icon: 'success'
                                }).then(function () {
                                    window.location.href = "{{ route('campaigns') }}";
                                });
                            },
                            error: function (response) {
                                console.log(response);
                                Swal.fire(
                                    'Error!',
                                    'There was a problem deleting the campaign.',
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
