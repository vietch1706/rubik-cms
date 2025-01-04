@php use Illuminate\Support\Facades\Session; @endphp
@extends('layout.app')
@section('content')
    <form method="POST" action="{{ route('products.update',['id' => $product['id']]) }}"
          enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="row">
            <div class="col-md-6 mb-3 ">
                <label class="form-label">Name <span class="required"> * </span></label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $product['name'] }}">
                @error('name')
                <span class="text-danger error">{{ $errors->first('name') }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Slug <span class="required"> * </span></label>
                <input type="text" class="form-control" id="slug" name="slug" value="{{ $product['slug'] }}" readonly>
                @error('slug')
                <span class="text-danger error">{{ $errors->first('slug') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Category <span class="required"> * </span></label>
                <select class="form-control select2" name="category_id" id="category_box">
                    <option value="">Select Category</option>
                    @foreach($categories as $key => $category)
                        <option value="{{$key}}" @if($key == key($product['category'])) selected @endif>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                <span class="text-danger error">{{ $errors->first('category_id') }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Brand <span class="required"> * </span></label>
                <select class="form-control select2" name="brand_id" id="brand_box">
                    <option value="">Select Brand</option>
                    @foreach($brands as $key => $brand)
                        <option value="{{$key}}" @if($key == key($product['brand'])) selected @endif>
                            {{ $brand }}
                        </option>
                    @endforeach
                </select>
                @error('brand_id')
                <span class="text-danger error">{{ $errors->first('brand_id') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3 ">
                <label class="form-label">SKU <span class="required"> * </span></label>
                <input type="text" class="form-control" name="sku" value="{{ $product['sku'] }}">
                @error('sku')
                <span class="text-danger error">{{ $errors->first('sku') }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Distributor <span class="required"> * </span></label>
                <select class="form-control select2" name="distributor_id">
                    <option value="">Select Distributor</option>
                    @foreach($distributors as $key => $distributor)
                        <option value="{{$key}}" @if($key == key($product['distributor'])) selected @endif>
                            {{ $distributor }}
                        </option>
                    @endforeach
                </select>
                @error('distributor_id')
                <span class="text-danger error">{{ $errors->first('distributor_id') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Quantity <span class="required"> * </span></label>
                <input type="number" class="form-control" name="quantity" value="{{ $product['quantity'] }}">
                @error('quantity')
                <span class="text-danger error">{{ $errors->first('quantity') }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3 ">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                    @foreach($statuses as $key => $status)
                        <option value="{{$key}}" @if($key == $product['status']) selected @endif>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Weight (g)<span class="note"> (The weight of the Rubik's cube) </span> <span
                        class="required"> * </span></label>
                <input type="number" class="form-control" name="weight" value="{{ $product['weight'] }}">
                @error('weight')
                <span class="text-danger error">{{ $errors->first('weight') }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Box Weight (g)<span class="note"> (Including accessories) </span>
                    <span class="required"> * </span></label>
                <input type="number" class="form-control" name="box_weight" value="{{ $product['box_weight'] }}">
                @error('box_weight')
                <span class="text-danger error">{{ $errors->first('box_weight') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3 ">
                <label class="form-label">Magnetic</label>
                <select class="form-select" name="magnetic">
                    @foreach($magnetics as $key => $magnetic)
                        <option value="{{$key}}" @if($key == $product['magnetic']) selected @endif>
                            {{ $magnetic }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3 ">
                <label class="form-label">Release Date <span class="required"> * </span></label>
                <input class="form-control" type="datetime-local" name="release_date"
                       value="{{ $product['release_date'] }}">
                @error('release_date')
                <span class="text-danger error">{{ $errors->first('release_date') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Image </label>
                <input class="form-control" type="file" accept="image/png, image/jpeg, image/jpg" name="image">
                @error('image')
                <span class="text-danger error">{{ $errors->first('image') }}</span>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Created At </label>
                <input type="datetime-local" class="form-control" value="{{ $product['created_at'] }}" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Updated At </label>
                <input type="datetime-local" class="form-control" value="{{ $product['updated_at'] }}" readonly>
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
                   href="{{ route('products') }}">Cancel</a>
            </div>
            <div class="right-item">
                <button class="delete-item" data-id="{{ $product['id'] }}">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </div>
        </div>
    </form>
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

        $(document).ready(function () {
            $('.select2').select2({
                placeholder: "Search and select an option",
                allowClear: true,
                theme: 'bootstrap-5',
                width: '100%'
            });
        });

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
                            url: "{{ route('products.destroy') }}",
                            type: "DELETE",
                            data: {
                                ids: selectedIds,
                                _token: "{{ csrf_token() }}",
                            },
                            success: function (response) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Your selected product have been deleted.',
                                    icon: 'success'
                                }).then(function () {
                                    window.location.href = "{{ route('products') }}";
                                });
                            },
                            error: function (response) {
                                console.log(response);
                                Swal.fire(
                                    'Error!',
                                    'There was a problem deleting the product.',
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
