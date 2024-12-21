@extends('layout.app')
@section('content')
    {{--    <link rel="stylesheet" href="{{ asset('css/list.css') }}">--}}
    <div class="container-fluid">
        <div class="action-buttons">
            <a href="{{ route('categories.create') }}" class="btn btn-primary">Create</a>
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
                        Parent Category
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
                @if(!empty($categories))
                    @foreach($categories as $category)
                        <tr class="text-nowrap hover-pointer" id="delete-id-{{ $category['id'] }}"
                            onclick="window.location='{{ route('categories.edit', $category['id']) }}'">
                            <td class="text-center">
                                <input type="checkbox" name="ids" class="checkbox-ids" value="{{ $category['id'] }}">
                            </td>
                            <td>
                                {{ $category['id'] }}
                            </td>
                            <td>
                                {{ $category['name'] }}
                            </td>
                            <td>
                                {{ $category['slug'] }}
                            </td>
                            @if($category['parent_category'])
                                <td>
                                    {{ current($category['parent_category']) }}
                                </td>
                            @else
                                <td class="text-danger" style="font-weight: bold; font-size: 20px;">
                                    Parent
                                </td>
                            @endif
                            <td>
                                {{ $category['created_at'] }}
                            </td>
                            <td>
                                {{ $category['updated_at'] }}
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
        <div class="pagination-container">{{$categories->links()}}</div>
    </div>
    <script src="{{ asset('/js/jQuery.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
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
                        title: 'No Categories Selected',
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
                            url: "{{ route('categories.destroy') }}",
                            type: "DELETE",
                            data: {
                                ids: selectedIds,
                                _token: "{{ csrf_token() }}",
                            },
                            success: function (response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Your selected categories have been deleted.',
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
                                    'There was a problem deleting the categories.',
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
