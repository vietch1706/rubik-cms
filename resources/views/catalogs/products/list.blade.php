@extends('layout.app')
@section('content')
    {{--    <link rel="stylesheet" href="{{ asset('css/list.css') }}">--}}
    <div class="container-fluid">
        <div class="action-buttons">
            <a href="{{ route('products.create') }}" class="btn btn-primary">Create</a>
            <button id="delete-record" class="btn btn-danger">Delete Selected</button>
        </div>
        <div class="table-responsive">
            <table
                class="table table-bordered table-striped table-hover">
                <thead>
                <tr class="text-nowrap col-md">
                    <th scope="col" class="px-3">
                        <input type="checkbox" name="" id="select-all-ids">
                    </th>
                    <th scope="col" class="pe-5 ">
                        ID
                    </th>
                    <th scope="col" class="pe-5 col-3">
                        Name
                    </th>
                    <th scope="col" class="pe-5 col-3">
                        Image
                    </th>
                    <th scope="col" class="pe-5 col-3">
                        SKU
                    </th>
                    <th scope="col" class="pe-5 col-3">
                        Release Date
                    </th>
                    <th scope="col" class="pe-5 col-3">
                        Brand
                    </th>
                    <th scope="col" class="pe-5 col-3">
                        Distributor
                    </th>
                    <th scope="col" class="pe-5 col-3">
                        Category
                    </th>
                    <th scope="col" class="pe-5 col-3">
                        Slug
                    </th>
                    <th scope="col" class="pe-5 col-3">
                        Status
                    </th>
                    <th scope="col" class="pe-5 col-3">
                        Price (thousand)
                    </th>
                    <th scope="col" class="pe-5 col-3">
                        Quantity
                    </th>
                    <th scope="col" class="pe-5 col-3">
                        Magnetic
                    </th>
                    <th scope="col" class="pe-5 col-3">
                        Weight (g)
                    </th>
                    <th scope="col" class="pe-5 col-3">
                        Box Weight (g)
                    </th>
                    <th scope="col" class="pe-5 col-3">
                        Created At
                    </th>
                    <th scope="col" class="pe-5 col-3">
                        Updated At
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($products as $product)
                    <tr class="text-nowrap hover-pointer" id="delete-id-{{ $product['id'] }}"
                        onclick="window.location='{{ route('products.edit', $product['id']) }}'">
                        <td class="text-center">
                            <input type="checkbox" name="ids" class="checkbox-ids" value="{{ $product['id'] }}">
                        </td>
                        <td>
                            {{ $product['id'] }}
                        </td>
                        <td>
                            {{ $product['name'] }}
                        </td>
                        <td>
                            @if($product['image'])
                                <img src="{{ url($product['image']) }}"
                                     alt="{{ $product['slug'] }}" width="75"
                                     height="50">
                            @endif
                        </td>
                        <td>{{ $product['sku'] }}</td>
                        <td>{{ $product['release_date'] }}</td>
                        <td>
                            @if($product['brand'])
                                {{ current($product['brand']) }}
                            @endif
                        </td>
                        <td>
                            @if($product['distributor'])
                                {{ current($product['distributor']) }}
                            @endif
                        </td>
                        <td>
                            @if($product['category'])
                                {{ current($product['category']) }}
                            @endif
                        </td>
                        <td>{{ $product['slug'] }}</td>
                        <td class=" h-100 {{ $class = $product['status'] != 0 ? 'text-success' : 'text-danger' }}">
                            <i class="fa-solid fa-circle"></i>
                            {{ $product['status'] != 0 ? 'Available' : 'Unavailable' }}
                        </td>
                        <td>{{ $product['price'] }}</td>
                        <td>{{ $product['quantity'] }}</td>
                        <td>{{ $product['magnetic'] }}</td>
                        <td>{{ $product['weight'] }}</td>
                        <td>{{ $product['box_weight'] }}</td>
                        <td>
                            {{ $product['created_at'] }}
                        </td>
                        <td>
                            {{ $product['updated_at'] }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination-container">{{$products->links()}}</div>
    </div>
    <script src="{{ asset('/js/jquery-3.7.1.min.js') }}"></script>
    <script type="text/javascript">
        @if (Session::has('success'))
        Swal.fire(
            '{{ Session::get('success') }}',
            '',
            'success'
        );
        @endif

        $(function (e) {
            $('input[name="ids"]').click(function (e) {
                e.stopPropagation();
            });

            $('#select-all-ids').click(function () {
                $('.checkbox-ids').prop('checked', $(this).prop('checked'));
            });

            $('#delete-record').click(function (e) {
                e.preventDefault();

                var selectedIds = [];
                $('input[name="ids"]:checked').each(function () {
                    selectedIds.push($(this).val());
                });
                if (selectedIds.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Products Selected',
                        text: 'Please select at least one customer to delete.',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this action!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, keep it',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Perform delete action here
                        $.ajax({
                            url: "{{ route('products.destroy') }}",
                            type: "DELETE",
                            data: {
                                ids: selectedIds,
                                _token: "{{ csrf_token() }}",
                            },
                            success: function (response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Your selected products have been deleted.',
                                    'success'
                                );
                                $.each(selectedIds, function (key, val) {
                                    $('#delete-id-' + val).remove();
                                });
                                window.location.reload();

                            },
                            error: function () {
                                Swal.fire(
                                    'Error!',
                                    'There was a problem deleting the products.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
