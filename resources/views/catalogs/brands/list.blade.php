@extends('layout.app')
@section('content')
    {{--    <link rel="stylesheet" href="{{ asset('css/list.css') }}">--}}
    <div class="action-buttons">
        <a href="{{ route('brands.create') }}" class="btn btn-primary">Create</a>
        <button id="delete-record" class="btn btn-danger">Delete Selected</button>
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
            <tbody>
            @if(!empty($brands))
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
            @endif
            </tbody>
        </table>
    </div>
    <div class="pagination-container">{{$brands->links()}}</div>
    <script src="{{ asset('/js/jquery.js') }}"></script>
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
                        title: 'No Brands Selected',
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
                                window.location.reload();

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
