@extends('layout.app')
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <div class="action-buttons">
                <a href="{{ route('orders.create') }}" class="btn btn-primary py-2"><i class="fa-solid fa-plus"></i>
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
            <table class="table table-bordered table-striped table-hover ">
                <thead>
                <tr class="text-nowrap col-md">
                    <th scope="col" class="px-3" style="width: 25px; height: 25px; text-align: center;">
                        <input class="checkbox-size" type="checkbox" name=""
                               id="select-all-ids">
                    </th>
                    <th scope="col" class="pe-5">
                        ID
                    </th>
                    <th scope="col" class="pe-5">
                        Order No
                    </th>
                    <th scope="col" class="pe-5">
                        Distributor
                    </th>
                    <th scope="col" class="pe-5">
                        Employee
                    </th>
                    <th scope="col" class="pe-5">
                        Date
                    </th>
                    <th scope="col" class="pe-5">
                        Status
                    </th>
                    <th scope="col" class="pe-5">
                        Note
                    </th>
                    <th scope="col" class="pe-5">
                        Created At
                    </th>
                    <th scope="col" class="pe-5">
                        Updated At
                    </th>
                </tr>
                </thead>
                @if(!empty($orders))
                    <tbody>
                    @foreach($orders as $order)
                        <tr class="text-nowrap hover-pointer" id="delete-id-{{$order['id']}}"
                            onclick="window.location='{{ route('orders.preview', $order['id']) }}'">
                            <td class="text-center">
                                <input type="checkbox" name="ids" class="checkbox-ids"
                                       value="{{ $order['id'] }}">
                            </td>
                            <td>
                                {{ $order['id'] }}
                            </td>
                            <td>
                                {{ $order['order_number'] }}
                            </td>
                            @if($order['distributor'])
                                <td>
                                    {{ current($order['distributor']) }}
                                </td>
                            @else
                                <td>

                                </td>
                            @endif
                            @if($order['employee'])
                                <td>
                                    {{ current($order['employee']) }}
                                </td>
                            @else
                                <td>

                                </td>
                            @endif
                            <td>
                                {{ $order['date'] }}
                            </td>
                            <td @class([
                                'h-100',
                                'text-primary' => $order['status'] === 0,
                                'text-warning' => $order['status'] === 1,
                                'text-success' => $order['status'] === 2,
                                'text-danger' => !in_array($order['status'], [0, 1, 2 ]),
                            ])>
                                <i class="fa-solid fa-circle"></i>
                                {{
                                    $order['status'] === 0 ? 'Pending' :
                                    ($order['status'] === 1 ? 'Partially Imported' :
                                    ($order['status'] === 2 ? 'Fully Imported' :
                                    'Canceled'))
                                }}
                            </td>
                            <td title="{{ $order['note'] }}">
                                {{ Str::limit($order['note'], 50) }}
                            </td>
                            <td>
                                {{ $order['created_at'] }}
                            </td>
                            <td>
                                {{ $order['updated_at'] }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
            </table>
        </div>
        <div class="pagination-container">{{$orders->links()}}</div>
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
                        title: 'No OrderSeeder Selected',
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
                        $.ajax({
                            url: "{{ route('orders.destroy') }}",
                            type: "DELETE",
                            data: {
                                ids: selectedIds,
                                _token: "{{ csrf_token() }}",
                            },
                            success: function (response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Your selected orders have been deleted.',
                                    'success'
                                );
                                $.each(selectedIds, function (key, val) {
                                    $('#delete-id-' + val).remove();
                                });
                            },
                            error: function () {
                                Swal.fire(
                                    'Error!',
                                    'There was a problem deleting the orders.',
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
