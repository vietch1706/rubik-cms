@extends('layout.app')
@section('content')
{{--    <link rel="stylesheet" href="{{ asset('css/list.css') }}">--}}
    <div class="action-buttons">
        <a href="{{ route('distributors.create') }}" class="btn btn-primary">Create</a>
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
                    Address
                </th>
                <th scope="col" class="pe-5 col-3">
                    Country
                </th>
                <th scope="col" class="pe-5 col-3">
                    Phone
                </th>
                <th scope="col" class="pe-5 col-3">
                    Email
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
            @if(!empty($distributors))
                @foreach($distributors as $distributor)
                    <tr class="text-nowrap hover-pointer" id="delete-id-{{ $distributor['id'] }}"
                        onclick="window.location='{{ route('distributors.edit', $distributor['id']) }}'">
                        <td class="text-center">
                            <input type="checkbox" name="ids" class="checkbox-ids" value="{{ $distributor['id'] }}">
                        </td>
                        <td>
                            {{ $distributor['id'] }}
                        </td>
                        <td>
                            {{ $distributor['name'] }}
                        </td>
                        <td>
                            {{ $distributor['address'] }}
                        </td>
                        <td>
                            {{ $distributor['country'] }}
                        </td>
                        <td>
                            {{ $distributor['phone'] }}
                        </td>
                        <td>
                            {{ $distributor['email'] }}
                        </td>
                        <td>
                            {{ $distributor['created_at'] }}
                        </td>
                        <td>
                            {{ $distributor['updated_at'] }}
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <div class="pagination-container">{{$distributors->links()}}</div>
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
                        title: 'No Distributors Selected',
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
                            url: "{{ route('distributors.destroy') }}",
                            type: "DELETE",
                            data: {
                                ids: selectedIds,
                                _token: "{{ csrf_token() }}",
                            },
                            success: function (response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Your selected distributors have been deleted.',
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
                                    'There was a problem deleting the distributors.',
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
