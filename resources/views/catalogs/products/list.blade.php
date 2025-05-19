@php
    use Illuminate\Support\Facades\Request;
@endphp
@extends('layouts.app')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-latte-text">Products</h1>
            <div class="flex space-x-3">
                <a href="{{ route('admin.catalogs.products.create') }}"
                    class="bg-latte-blue text-white px-4 py-2 rounded-md hover:bg-latte-sapphire transition-colors duration-200">
                    <i class="fa-solid fa-plus mr-2"></i>Add Product
                </a>
                <button id="delete-selected"
                    class="hidden bg-latte-red text-white px-4 py-2 rounded-md hover:bg-latte-maroon transition-colors duration-200">
                    <i class="fa-solid fa-trash mr-2"></i>Delete Selected
                </button>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-latte-surface0 p-4 rounded-md shadow-sm border border-latte-surface1">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" id="search-input" placeholder="Search products..."
                        class="w-full px-3 py-2 rounded-md border border-latte-surface1 bg-latte-base text-latte-text focus:outline-none focus:ring-2 focus:ring-latte-lavender placeholder-latte-subtext0">
                </div>
                <div class="flex space-x-2">
                    <button id="search-button"
                        class="bg-latte-blue text-white px-4 py-2 rounded-md hover:bg-latte-sapphire transition-colors duration-200">
                        <i class="fa-solid fa-search"></i>
                    </button>
                    <button id="clear-search"
                        class="bg-latte-surface1 text-latte-text px-4 py-2 rounded-md hover:bg-latte-surface2 transition-colors duration-200">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="overflow-x-auto bg-white rounded-lg shadow-sm border border-latte-surface0">
            <table class="w-full text-sm text-left text-latte-text" id="products-table">
                <thead class="text-xs uppercase bg-latte-surface0 text-latte-subtext1">
                    <tr>
                        <th scope="col" class="px-3 py-3">
                            <input type="checkbox" id="select-all-ids" class="focus:ring-latte-lavender">
                        </th>
                        <th scope="col" class="px-5 py-3">ID</th>
                        <th scope="col" class="px-5 py-3">Name</th>
                        <th scope="col" class="px-5 py-3">Image</th>
                        <th scope="col" class="px-5 py-3">SKU</th>
                        <th scope="col" class="px-5 py-3">Release Date</th>
                        <th scope="col" class="px-5 py-3">Brand</th>
                        <th scope="col" class="px-5 py-3">Distributor</th>
                        <th scope="col" class="px-5 py-3">Category</th>
                        <th scope="col" class="px-5 py-3">Slug</th>
                        <th scope="col" class="px-5 py-3">Status</th>
                        <th scope="col" class="px-5 py-3">Price (thousand)</th>
                        <th scope="col" class="px-5 py-3">Quantity</th>
                        <th scope="col" class="px-5 py-3">Magnetic</th>
                        <th scope="col" class="px-5 py-3">Weight (g)</th>
                        <th scope="col" class="px-5 py-3">Box Weight (g)</th>
                        <th scope="col" class="px-5 py-3">Created At</th>
                        <th scope="col" class="px-5 py-3">Updated At</th>
                        <th scope="col" class="px-5 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody id="products-tbody">
                    @include('catalogs.products.table-body')
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4" id="pagination">
            @if (isset($products) && $products->hasPages())
                {{ $products->links('pagination') }}
            @endif
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('/js/jquery-3.7.1.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            const deleteUrl = '{{ route('admin.catalogs.products.destroy') }}';
            const csrfToken = '{{ csrf_token() }}';

            function toggleDeleteButton() {
                const anyChecked = $('.product-checkbox:checked').length > 0;
                $('#delete-selected').toggleClass('hidden', !anyChecked);
            }

            function deleteProducts(ids, title, confirmText) {
                Swal.fire({
                    title: title,
                    text: confirmText,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1e66f5',
                    cancelButtonColor: '#d20f39',
                    confirmButtonText: `Yes, delete ${ids.length > 1 ? 'them' : 'it'}`
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            data: {
                                ids: ids
                            },
                            success: (response) => {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: ids.length > 1 ?
                                        'Selected products have been deleted.' :
                                        'Product has been deleted.',
                                    icon: 'success',
                                    confirmButtonColor: '#1e66f5'
                                }).then(() => {
                                    window.location.reload();
                                    $('#select-all-ids').prop('checked', false);
                                });
                            },
                            error: (xhr) => {
                                Swal.fire({
                                    title: 'Error!',
                                    text: ids.length > 1 ?
                                        'Failed to delete selected products.' :
                                        'Failed to delete product.',
                                    icon: 'error',
                                    confirmButtonColor: '#1e66f5'
                                });
                            }
                        });
                    }
                });
            }

            $('#select-all-ids').on('change', function() {
                $('.product-checkbox').prop('checked', $(this).prop('checked'));
                toggleDeleteButton();
            });

            $(document).on('change', '.product-checkbox', function() {
                toggleDeleteButton();
                if (!$('.product-checkbox:checked').length) {
                    $('#select-all-ids').prop('checked', false);
                }
            });

            $(document).on('click', '.delete-product', function(e) {
                const id = $(this).data('id');
                deleteProducts([id], 'Delete Product', 'Are you sure you want to delete this product?');
            });

            $('#delete-selected').on('click', function() {
                const selectedIds = $('.product-checkbox:checked').map((_, el) => $(el).val()).get();
                if (selectedIds.length === 0) {
                    Swal.fire({
                        title: 'No Selection',
                        text: 'Please select at least one product to delete.',
                        icon: 'warning',
                        confirmButtonColor: '#1e66f5'
                    });
                    return;
                }
                deleteProducts(
                    selectedIds,
                    'Delete Selected Products',
                    `Are you sure you want to delete ${selectedIds.length} product(s)?`
                );
            });
        });
    </script>
@endsection
