@php use Illuminate\Support\Facades\Session; @endphp
@extends('layout.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}" type="text/css">
    <form method="POST" action="{{ route('campaigns.update', ['id' => $campaign['id']]) }}"
          enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="row">
            <div class="col-md-6 mb-3 ">
                <label class="form-label">Name <span class="required"> * </span></label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $campaign['name'] }}">
                @error('name')
                <span class="text-danger error">{{ $errors->first('name') }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Slug <span class="required"> * </span></label>
                <input type="text" class="form-control" id="slug" name="slug" value="{{ $campaign['slug'] }}" readonly>
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
                        <option value="{{$key}}" @if($type == $campaign['type']) selected @endif>
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
                        <option value="{{$key}}" @if($key == $campaign['status']) selected @endif>
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
                <input type="date" class="form-control" value="{{ $campaign['start_date'] }}" name="start_date">
                @error('start_date')
                <span class="text-danger error">{{ $errors->first('start_date') }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">End Date </label>
                <input type="date" class="form-control" value="{{ $campaign['end_date'] }}" name="end_date">
                @error('end_date')
                <span class="text-danger error">{{ $errors->first('end_date') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col mb-3" id="discountValueField" style="display: none;">
                <label class="form-label">Discount Value</label>
                <input type="text" class="form-control" name="discount_value" value="{{$campaign['discount_value']}}">
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
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Created At </label>
                <input type="datetime-local" class="form-control" value="{{ $campaign['created_at'] }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Updated At </label>
                <input type="datetime-local" class="form-control" value="{{ $campaign['updated_at'] }}" readonly>
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

        toggleTypeFields();
        $('#type').change(function () {
            toggleTypeFields();
        });

        function toggleTypeFields() {
            const type = $('#type').val();
            const campaign = @json($campaign);
            const bundleValue = Object.keys(campaign.bundle_value).toString();
            $('#discountValueField').hide();
            $('#bundleField').hide();
            if (type === '') {
                return;
            }
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
                                    `<option value="${product.id}" ${product.sku == bundleValue ? 'selected' : ''}>${product.name} - ${product.sku}</option>`
                                )
                            });
                        } else {
                            $('#productSelect').append('<option value="">No products available</option>');
                        }
                    },
                });
                $('#bundleField').show();
            }
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
