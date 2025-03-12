@php use Illuminate\Support\Facades\Session; @endphp
@extends('layout.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}" type="text/css">
    <form method="POST" action="{{ route('campaigns.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3 ">
                <label class="form-label">Name <span class="required"> * </span></label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                @error('name')
                <span class="text-danger error">{{ $errors->first('name') }}</span>
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
            <div class="col-md-6 mb-3">
                <label class="form-label">Start Date </label>
                <input type="date" class="form-control" value="{{ old('start_date') }}" name="start_date">
                @error('start_date')
                <span class="text-danger error">{{ $errors->first('start_date') }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">End Date </label>
                <input type="date" class="form-control" value="{{ old('end_date') }}" name="end_date">
                @error('end_date')
                <span class="text-danger error">{{ $errors->first('end_date') }}</span>
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
            const nameInput = $('#name');
            const slugInput = $('#slug');

            nameInput.on('input', function () {
                slugInput.val(generateSlug(nameInput.val()));
            });

            $('#type').change(function () {
                toggleTypeFields();
            });

            function toggleTypeFields() {
                const type = $('#type').val();
                $('#discountValueField').hide();
                $('#bundleField').hide();
                if (type === '') {
                    $('#discountValueField').hide();
                    $('#bundleField').hide();
                } else {
                    if (type == 0) {
                        $('#discountValueField').show();
                    } else if (type == 1) {
                        $('#productSelect').empty().append('<option value="">Select Product</option>');
                        var url = '{{ url('api/v1/products/get') }}';
                        $.ajax({
                            url: url,
                            type: 'GET',
                            success: function (response) {
                                if (response.data && response.data.length > 0) {
                                    response.data.forEach(function (product) {
                                        $('#productSelect').append(
                                            `<option value="${product.id}" data-name="${product.name}">${product.name} - ${product.sku}</option>`
                                        );
                                    });
                                } else {
                                    $('#productSelect').append('<option value="">No products available</option>');
                                }
                            },
                        });
                        $('#bundleField').show();
                    }
                }
            }

            function generateSlug(name) {
                return name
                    .toLowerCase()
                    .replace(/\s+/g, '-');
            }
        });

        function setAction(action) {
            $('#actionType').val(action);
        }

    </script>
@endsection
