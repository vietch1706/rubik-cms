@extends('layout.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/list.css') }}" type="text/css">
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <div class="action-buttons">
                <button id="clear-logs" class="btn btn-danger py-2"><i class="fa-solid fa-trash-can"></i> Clear Logs
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
                    <th scope="col" class="pe-5">
                        ID
                    </th>
                    <th scope="col" class="pe-5">
                        Environment
                    </th>
                    <th scope="col" class="pe-5">
                        Level
                    </th>
                    <th scope="col" class="pe-5">
                        Message
                    </th>
                    <th scope="col" class="pe-5">
                        Created At
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($logs as $key => $log)
                    <tr class="text-nowrap hover-pointer" onclick="window.location='{{ route('logs.preview', $key) }}'">
                        <td>
                            {{ $key }}
                        </td>
                        <td>
                            {{ $log['environment'] }}
                        </td>
                        <td @class(['text-danger' => $log['level'] == 'ERROR'])>
                            {{ $log['level'] }}
                        </td>
                        <td title="{{ $log['message'] }}">
                            {{ Str::limit($log['message'], 50) }}
                        </td>
                        <td>
                            {{ $log['timestamp'] }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination-container">{{$logs->links()}}</div>
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
            $('#clear-logs').click(function (e) {
                e.preventDefault();

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
                            url: "{{ route('logs.destroy') }}",
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}",
                            },
                            success: function (response) {
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                );
                                $('#logs-table tbody').empty();
                            },
                            error: function () {
                                Swal.fire(
                                    'Error!',
                                    'There was a problem deleting the logs.',
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
