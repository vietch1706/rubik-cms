@php use App\Models\ImportReceipt;use App\Models\Transactions\Invoices; @endphp
@extends('layout.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/list.css') }}" type="text/css">
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <div class="action-buttons">
                <a href="{{ route('invoices.create') }}" class="btn btn-primary py-2"><i class="fa-solid fa-plus"></i>
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
                    <th scope="col" class="px-3" style="width: 25px; height: 25px; text-align: center;">
                        <input class="checkbox-size" type="checkbox" name=""
                               id="select-all-ids">
                    </th>
                    <th scope="col" class="pe-5">
                        ID
                    </th>
                    <th scope="col" class="pe-5">
                        Employee
                    </th>
                    <th scope="col" class="pe-5">
                        Customer
                    </th>
                    <th scope="col" class="pe-5">
                        Date
                    </th>
                    <th scope="col" class="pe-5">
                        Status
                    </th>
                    <th scope="col" class="pe-5">
                        Created At
                    </th>
                    <th scope="col" class="pe-5">
                        Updated At
                    </th>
                </tr>
                </thead>
                @if(!empty($invoices))
                    <tbody>
                    @foreach($invoices as $invoice)
                        <tr class="text-nowrap hover-pointer" id="delete-id-{{$invoice['id']}}"
                            onclick="window.location='{{ route('invoices.preview', $invoice['id']) }}'">
                            <td class="text-center">
                                <input type="checkbox" name="ids" class="checkbox-ids"
                                       value="{{ $invoice['id'] }}">
                            </td>
                            <td>
                                {{ $invoice['id'] }}
                            </td>
                            <td>
                                {{ current($invoice['employee']) }}
                            </td>
                            <td>
                                @if($invoice['customer'])
                                    {{ current($invoice['customer']) }}
                                @else
                            </td>
                            @endif
                            <td>
                                {{ $invoice['date'] }}
                            </td>
                            <td @class(['h-100','text-primary' => $invoice['status'] === Invoices::STATUS_PENDING,'text-info' => $invoice['status'] === Invoices::STATUS_PAYMENT_CONFIRM,'text-warning' => $invoice['status'] === Invoices::STATUS_PROCESS,'text-secondary' => $invoice['status'] === Invoices::STATUS_SHIPPED,'text-success' => $invoice['status'] === Invoices::STATUS_DELIVERED,'text-success' => $invoice['status'] === Invoices::STATUS_COMPLETED,'text-danger' => $invoice['status'] === Invoices::STATUS_CANCELLED,'text-danger' => $invoice['status'] === Invoices::STATUS_REFUNDED,])>
                                <i class="fa-solid fa-circle"></i>
                                {{$invoice['status'] === Invoices::STATUS_PENDING ? 'Pending' :($invoice['status'] === Invoices::STATUS_PAYMENT_CONFIRM ? 'Payment Confirmed' :($invoice['status'] === Invoices::STATUS_PROCESS ? 'Processing' :($invoice['status'] === Invoices::STATUS_SHIPPED ? 'Shipped' :($invoice['status'] === Invoices::STATUS_DELIVERED ? 'Delivered' :($invoice['status'] === Invoices::STATUS_COMPLETED ? 'Completed' :($invoice['status'] === Invoices::STATUS_CANCELLED ? 'Cancelled' : 'Refunded'))))))
                                }}
                            </td>

                            <td>
                                {{ $invoice['created_at'] }}
                            </td>
                            <td>
                                {{ $invoice['updated_at'] }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
            </table>
        </div>
        <div class="pagination-container">{!! $link !!}</div>
        @endif
    </div>
    <script src="{{ asset('js/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('/js/jquery-3.7.1.min.js') }}"></script>
    <script type="text/javascript">
        @if (Session::has('success'))
        Swal.fire(
            '{{ Session::get('success') }}',
            '',
            'success'
        );
        @endif
        $('th').each(function () {
            if (!$(this).find('i').length && !$(this).find('input[type="checkbox"]').length) {
                $(this).append(' <i class="fa-solid fa-arrow-down-short-wide"></i>');
            }
        });

        $('th').click(function () {
            if ($(this).find('input[type="checkbox"]').length) {
                return;
            }
            var table = $(this).parents('table').eq(0);
            var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()));
            this.asc = !this.asc;

            $(this).find('i').remove();

            $(this).append(this.asc ? ' <i class="fa-solid fa-arrow-up-short-wide"></i>' : ' <i class="fa-solid fa-arrow-down-short-wide"></i>');

            if (!this.asc) {
                rows = rows.reverse();
            }

            for (var i = 0; i < rows.length; i++) {
                table.append(rows[i]);
            }
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
                        title: 'No OrderSeeder Selected',
                        text: 'Please select at least one invoice to delete.',
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
                            url: "{{ route('invoices.destroy') }}",
                            type: "DELETE",
                            data: {
                                ids: selectedIds,
                                _token: "{{ csrf_token() }}",
                            },
                            success: function (response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Your selected invoices have been deleted.',
                                    'success'
                                );
                                $.each(selectedIds, function (key, val) {
                                    $('#delete-id-' + val).remove();
                                });
                            },
                            error: function () {
                                Swal.fire(
                                    'Error!',
                                    'There was a problem deleting the invoices.',
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
