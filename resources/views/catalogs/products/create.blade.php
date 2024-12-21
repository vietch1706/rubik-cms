@php use Illuminate\Support\Facades\Session; @endphp
@extends('layout.app')
@section('content')
    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
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
            <div class="col-md-6 mb-3">
                <label class="form-label">Category <span class="required"> * </span></label>
                <select class="form-control select2" name="category_id" id="category_box">
                    <option value="">Select Category</option>
                    @foreach($categories as $key => $category)
                        <option value="{{$key}}" @if($key == old('category_id')) selected @endif>
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
                        <option value="{{$key}}" @if($key == old('brand_id')) selected @endif>
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
                <input type="text" class="form-control" name="sku" value="{{ old('sku') }}">
                @error('sku')
                <span class="text-danger error">{{ $errors->first('sku') }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Distributor <span class="required"> * </span></label>
                <select class="form-control select2" name="distributor_id">
                    <option value="">Select Distributor</option>
                    @foreach($distributors as $key => $distributor)
                        <option value="{{$key}}" @if($key == old('distributor_id')) selected @endif>
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
                <input type="number" class="form-control" name="quantity" value="{{ old('quantity') }}">
                @error('quantity')
                <span class="text-danger error">{{ $errors->first('quantity') }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3 ">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                    @foreach($statuses as $key => $status)
                        <option value="{{$key}}">
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Weight (g) <span class="required"> * </span></label>
                <input type="number" class="form-control" name="weight" value="{{ old('weight') }}">
                @error('weight')
                <span class="text-danger error">{{ $errors->first('weight') }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Box Weight (g) <span class="required"> * </span></label>
                <input type="number" class="form-control" name="box_weight" value="{{ old('box_weight') }}">
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
                        <option value="{{$key}}">
                            {{ $magnetic }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3 ">
                <label class="form-label">Release Date <span class="required"> * </span></label>
                <input class="form-control" type="datetime-local" name="release_date"
                       value="{{ old('release_date') }}">
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
        <input type="hidden" name="action" id="actionType" value="save">
        <button type="submit" class="btn btn-primary me-3">Save</button>
        <button type="submit" class="btn btn-secondary me-3" onclick="setAction('save_and_close')">Save and Close
        </button>
        <span>Or</span>
        <a type="submit" class="link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
           href="{{ route('products') }}">Cancel</a>
    </form>

    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('/js/jQuery.js') }}"></script>
    <script src="{{ asset('/js/select2.min.js') }}"></script>

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


        function setAction(action) {
            document.getElementById('actionType').value = action;
        }
    </script>
@endsection
