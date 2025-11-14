@extends('layout')

@section('title')
    <title>Queue | PRA</title>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">

            <div class="d-sm-flex align-items-center justify-content-between mb-7">
                <h1 class="h3 mb-0 text-gray-800" style="margin: auto"><strong><i class="fa-solid fa-list-ol"></i> &nbsp;
                        Queue</strong></h1>
            </div>

            <div class="row mb-4">
                <!-- Button group aligned to the right -->
                <div class="col text-end">
                    <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                        <input type="radio" class="btn-check" name="btnradio" id="btn-in-progress" autocomplete="off"
                            checked>
                        <label class="btn btn-outline-primary" for="btn-in-progress">Incomplete</label>
                        <input type="radio" class="btn-check" name="btnradio" id="btn-all" autocomplete="off">
                        <label class="btn btn-outline-primary" for="btn-all">All</label>
                        <input type="radio" class="btn-check" name="btnradio" id="btn-completed" autocomplete="off">
                        <label class="btn btn-outline-primary" for="btn-completed">Completed</label>
                    </div>
                </div>
            </div>

            <table id="queue-table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Item Type</th>
                        <th>Item Code</th>
                        <th>Requesting Unit</th>
                        <th>Item</th>
                        <th>Date Received</th>
                        <th>Status</th>
                        <th>View</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#queue-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('getInProgressQueue') }}',
                order: [
                    [4, 'desc']
                ],
                columns: [{
                        data: 'item_type',
                        name: 'item_type'
                    },
                    {
                        data: 'item_code',
                        name: 'item_code'
                    },
                    {
                        data: 'requesting_unit',
                        name: 'requesting_unit'
                    },
                    {
                        data: 'item',
                        name: 'item'
                    },
                    {
                        data: 'date_received',
                        name: 'date_received',
                        render: function(data) {
                            if (data) {
                                var date = new Date(data);
                                var day = date.getDate();
                                var month = date.getMonth() + 1;
                                var year = date.getFullYear();
                                return (day < 10 ? '0' : '') + day + '/' + (month < 10 ? '0' : '') +
                                    month + '/' + year;
                            }
                            return '';
                        }
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'view',
                        name: 'view',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $('#btn-all').on('click', function() {
                table.ajax.url('{{ route('getqueue') }}').load();
            });

            $('#btn-in-progress').on('click', function() {
                table.ajax.url('{{ route('getInProgressQueue') }}').load();
            });

            $('#btn-completed').on('click', function() {
                table.ajax.url('{{ route('getCompletedQueue') }}').load();
            });
        });
    </script>
@endsection
