@extends('layout')

@section('title')
    <title>Check Staff Requisitions | PRA</title>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">

            <div class="d-sm-flex align-items-center justify-content-between mb-7">
                <h1 class="h3 mb-0 text-gray-800" style="margin: auto"><strong><i class="fa-solid fa-file-import"></i>
                        &nbsp;Requisitions</strong></h1>
            </div>

            <div class="row mb-4">
                <!-- Button group aligned to the right -->
                <div class="col text-end">
                    <div class="btn-group" role="group" aria-label="Basic radio toggle button group">

                        <input type="radio" class="btn-check" name="btnradio" id="btn-all" autocomplete="off" checked>
                        <label class="btn btn-outline-primary" for="btn-all">All</label>

                        <input type="radio" class="btn-check" name="btnradio" id="btn-in-progress" autocomplete="off">
                        <label class="btn btn-outline-primary" for="btn-in-progress">In Progress</label>

                        <input type="radio" class="btn-check" name="btnradio" id="btn-completed" autocomplete="off">
                        <label class="btn btn-outline-primary" for="btn-completed">Completed</label>
                    </div>
                </div>
            </div>

            <table id="myTable" class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>Requisition #</th>
                        <th>Requesting Unit</th>
                        <th>Item</th>
                        <th>Date Received</th>
                        <th style="text-align: center">Status</th>
                        <th style="width: 20%;text-align: center">Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                </tbody>
            </table>

        </div>
    </div>
@endsection


@section('scripts')
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                "pageLength": 10,
                order: [
                    [6, 'desc']
                ],
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('getcheckroom_requisitions') }}",
                    "type": "GET"
                },
                "columns": [{
                        data: 'requisition_no',
                        name: 'requisition_no'
                    },
                    {
                        data: 'RequestingUnit',
                        name: 'departments.name'
                    }, // Correct field for searching
                    {
                        data: 'ItemName',
                        name: 'requisitions.item'
                    },
                    {
                        data: 'cr_created_at',
                        name: 'check_room_requisitions.created_at',
                        // render: function(data, type, row) {
                        //     if (data) {
                        //         var date = new Date(data);
                        //         // Format the date as dd/mm/yyyy
                        //         var day = ('0' + date.getDate()).slice(-
                        //             2); // Add leading 0 if necessary
                        //         var month = ('0' + (date.getMonth() + 1)).slice(-
                        //             2); // Add leading 0, note months are 0-indexed
                        //         var year = date.getFullYear();
                        //         return day + '/' + month + '/' + year;
                        //     }
                        //     return ''; // Return an empty string if there's no date
                        // }
                    },
                    {
                        data: 'cr_completed',
                        name: 'check_room_requisitions.is_completed',
                        render: function(data, type, row) {
                            var status = row.requisition_status ||
                                'In Progress'; // Fallback in case `requisition_status` is empty
                            if (status === 'Sent to Check Staff') {
                                return '<div style="text-align:center;"><span style="background-color: #e09e03 !important;" class="badge bg-warning">Received from Vote Control</span></div>';
                            } else if (data === false || data == 0) {
                                return '<div style="text-align:center;"><span style="background-color: #e09e03 !important;" class="badge bg-warning">' +
                                    status + '</span></div>';
                            } else {
                                return '<div style="text-align:center;"><span style="background-color: #47a102 !important;" class="badge bg-success">Completed</span></div>';
                            }
                        },
                        orderable: false, // Optional: You can make this column non-orderable
                        searchable: false // Optional: You can make this column non-searchable
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false,
                        // render: function(data, type, row) {
                        //     return ' <div style="text-align:center"><a class="btn btn-primary" href="/check_room/view/' +
                        //         data.cr_id + '" >View</a> </div>';
                        // }
                    },
                    {
                        data: 'cr_id',
                        name: 'check_room_requisitions.id',
                        visible: false // Hide the column but use it for sorting
                    }
                ]
            });
        });

        $('.btn-check').change(function() { //Table Filter
            var selectedOption = $("input[name='btnradio']:checked").attr('id');
            switch (selectedOption) {
                case 'btn-in-progress':
                    $('#myTable').DataTable().ajax.url('{{ route('getcheckroom_requisitions') }}')
                        .load();
                    break;
                case 'btn-completed':
                    $('#myTable').DataTable().ajax.url('{{ route('getcompletedcheckroom_requisitions') }}')
                        .load();
                    break;
                case 'btn-all':
                    $('#myTable').DataTable().ajax.url('{{ route('getcheckroom_requisitions') }}').load();
                    break;
            }
        });


        window.addEventListener('refresh-table', event => {
            $('#myTable').DataTable().ajax.reload();
        })
    </script>
@endsection
