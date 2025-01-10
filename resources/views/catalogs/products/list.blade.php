@extends('layout.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/list.css') }}" type="text/css">
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <div class="action-buttons">
                <a href="{{ route('products.create') }}" class="btn btn-primary py-2"><i class="fa-solid fa-plus"></i>
                    Create</a>
                <button id="delete-record" class="btn btn-danger py-2"><i class="fa-solid fa-trash-can"></i> Delete
                </button>
            </div>
            <div class="input-group w-25">
                <span class="input-group-text" id="basic-addon1">
                 <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input
                    type="text"
                    id="search"
                    class="search-box form-control h-100 py-2 pl-5"
                    placeholder="Search Here ..."
                    autocomplete="off"
                    aria-describedby="basic-addon2">
            </div>
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
                        Gallery
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
                @if(!empty($products))
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
                            <td>
                                @if($product['gallery'])
                                    @foreach(array_slice($product['gallery'], 0, 3) as $gallery)
                                        <img src="{{ url($gallery) }}"
                                             alt="{{$product['slug']}}" width="75"
                                             height="50">
                                    @endforeach
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
        @endif
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
        $('th').click(function () {
            var table = $(this).parents('table').eq(0)
            var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()))
            this.asc = !this.asc
            if (!this.asc) {
                rows = rows.reverse()
            }
            for (var i = 0; i < rows.length; i++) {
                table.append(rows[i])
            }
            $('th i').remove();
            $(this).append(this.asc ? ' <i class="fa-solid fa-arrow-up-short-wide"></i>' : ' <i class="fa-solid fa-arrow-down-short-wide"></i> ');
        })

        function comparer(index) {
            return function (a, b) {
                var valA = getCellValue(a, index), valB = getCellValue(b, index)
                return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB)
            }
        }

        function getCellValue(row, index) {
            return $(row).children('td').eq(index).text()
        }

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
                        text: 'Please select at least one product to delete.',
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
        $('#search').on('keyup', function (e) {
            e.preventDefault();
            let searchString = $(this).val();
            console.log(searchString);
            $.ajax({
                url: "{{ route('products.search') }}",
                method: 'GET',
                data: {'search': searchString},
                success: function (response) {
                    console.log(response.error)
                    if (response.error) {
                        $('tbody').html(
                            `<tr><td colspan="16" class="text-danger text-center" style="font-size: 20px;">${response.error}</td></tr>`
                        );
                        $('.pagination-container').html('');
                    } else {
                        $('tbody').html(response.products);
                        $('.pagination-container').html(response.pagination);
                    }
                },

            })
        });
    </script>
@endsection
