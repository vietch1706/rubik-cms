@php use Illuminate\Support\Facades\Auth;use Illuminate\Support\Facades\Session; @endphp
@extends('layout.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}" type="text/css">
    <form method="POST" action="{{ route('campaigns.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Employee <span class="required"> * </span></label>
                <input type="text" class="form-control" value="{{ $current_employee['full_name'] }}" readonly>
                <input type="hidden" name="employee_id" value="{{ $current_employee['id'] }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Date <span class="required"> * </span></label>
                <input class="form-control" type="datetime-local" name="date" id="date" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3 ">
                <label class="form-label">Title <span class="required"> * </span></label>
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}">
                @error('title')
                <span class="text-danger error">{{ $errors->first('title') }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Slug <span class="required"> * </span></label>
                <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug') }}">
                @error('slug')
                <span class="text-danger error">{{ $errors->first('slug') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Type</label>
                <select class="form-control select2-overwrite" name="type" id="type">
                    <option value="">Select Type</option>
                    @foreach($types as $key => $type)
                        <option value="{{$key}}">
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
                @error('type')
                <span class="text-danger error">{{ $errors->first('type') }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Status <span class="required"> * </span></label>
                <select class="form-control select2-overwrite" name="status">
                    <option value="">Select Status</option>
                    @foreach($statuses as $key => $status)
                        <option value="{{$key}}">
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
                @error('status')
                <span class="text-danger error">{{ $errors->first('status') }}</span>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col mb-3" id="discountValueField" style="display: none;">
                <label class="form-label">Discount Value</label>
                <input type="text" class="form-control" name="discount_value">
            </div>
            <div class="col mb-3" id="bundleField" style="display: none;">
                <label class="form-label">Bundle Options</label>
                <select class="form-control select2-overwrite" id="productSelect" name="bundle_value">
                    <option value="">Select Product</option>
                </select>
            </div>
            <div class="col mb-3">
                @error('discount_value')
                <span class="text-danger error">{{ $errors->first('discount_value') }}</span>
                @enderror
                @error('bundle_value')
                <span class="text-danger error">{{ $errors->first('bundle_value') }}</span>
                @enderror
            </div>
        </div>
        <input type="hidden" name="action" id="actionType" value="save">
        <button type="submit" class="btn btn-primary me-3">Save</button>
        <button type="submit" class="btn btn-secondary me-3" onclick="setAction('save_and_close')">Save and Close
        </button>
        <span>Or</span>
        <a type="submit" class="link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
           href="{{ route('campaigns') }}">Cancel</a>
    </form>
    <script src="{{ asset('/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2@11.js') }}"></script>
    <script>
        @if (Session::has('success'))
        Swal.fire(
            '{{ Session::get('success') }}',
            '',
            'success'
        );
        @endif

        $(document).ready(function () {
            const titleInput = $('#title');
            const slugInput = $('#slug');

            titleInput.on('input', function () {
                slugInput.val(generateSlug(titleInput.val()));
            });

            function generateSlug(title) {
                return title
                    .toLowerCase()
                    .replace(/\s+/g, '-');
            }
        });

        function setAction(action) {
            $('#actionType').val(action);
        }

    </script>
@endsection
