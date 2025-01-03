@extends('layout.app')
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <div class="action-buttons">
                <a href="{{ route('brands.create') }}" class="btn btn-primary py-2"><i class="fa-solid fa-plus"></i>
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
            <table class="table table-bordered table-striped table-hover">
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
                        Slug
                    </th>
                    <th scope="col" class="pe-5 col-3">
                        Image
                    </th>
                    <th scope="col" class="pe-5 col-3">
                        Created At
                    </th>
                    <th scope="col" class="pe-5 col-3">
                        Updated At
                    </th>
                </tr>
                </thead>
                @if(!empty($brands))
                    <tbody>
                    @foreach($brands as $brand)
                        <tr class="text-nowrap hover-pointer" id="delete-id-{{ $brand['id'] }}"
                            onclick="window.location='{{ route('brands.edit', $brand['id']) }}'">
                            <td class="text-center">
                                <input type="checkbox" name="ids" class="checkbox-ids" value="{{ $brand['id'] }}">
                            </td>
                            <td>
                                {{ $brand['id'] }}
                            </td>
                            <td>
                                {{ $brand['name'] }}
                            </td>
                            <td>
                                {{ $brand['slug'] }}
                            </td>
                            <td>
                                @if($brand['image'])
                                    <img src="{{ url($brand['image']) }}"
                                         alt="{{ $brand['slug'] }}" width="75"
                                         height="50">
                                @endif
                            </td>
                            <td>
                                {{ $brand['created_at'] }}
                            </td>
                            <td>
                                {{ $brand['updated_at'] }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
            </table>
        </div>
        <div class="pagination-container">{{$brands->links()}}</div>
        @endif
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
        $('#search').on('keyup', function (e) {
            e.preventDefault();
            let searchString = $(this).val();
            console.log(searchString);
            $.ajax({
                url: "{{ route('brands.search') }}",
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
                        $('tbody').html(response.brands);
                        $('.pagination-container').html(response.pagination);
                    }
                },

            })
        });
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
                        title: 'No Brands Selected',
                        text: 'Please select at least one brand to delete.',
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
                            url: "{{ route('brands.destroy') }}",
                            type: "DELETE",
                            data: {
                                ids: selectedIds,
                                _token: "{{ csrf_token() }}",
                            },
                            success: function (response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Your selected brands have been deleted.',
                                    'success'
                                );
                                $.each(selectedIds, function (key, val) {
                                    $('#delete-id-' + val).remove();
                                });
                            },
                            error: function () {
                                Swal.fire(
                                    'Error!',
                                    'There was a problem deleting the brands.',
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
