@extends('layout')

@section('title')
    <title>Cheque Processing Requisitions | PRA</title>
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
                        <th>Vendor</th>
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
                    "url": "{{ route('getchequeprocessing_vendors') }}",
                    "type": "GET"
                },
                "columns": [{
                        data: 'RequisitionNo',
                        name: 'requisitions.requisition_no'
                    },
                    {
                        data: 'VendorName',
                        name: 'requisition_vendors.vendor_name'
                    },
                    {
                        data: 'ItemName',
                        name: 'requisitions.item'
                    },
                    {
                        data: 'cp_created_at',
                        name: 'cheque_processing_vendors.created_at',
                    },
                    {
                        data: 'cp_completed',
                        name: 'cheque_processing_vendors.is_completed',
                        render: function(data, type, row) {
                            var status = row.VendorStatus ||
                                'In Progress'; // Fallback in case `requisition_status` is empty
                            if (status === 'Sent to Cheque Processing') {
                                return '<div style="text-align:center;"><span style="background-color: #e09e03 !important;" class="badge bg-warning">Received from Check Staff</span></div>';
                            } else if (status === 'Incomplete') {
                                return '<div style="text-align:center;"><span style="background-color: #e00303 !important;" class="badge bg-danger">' +
                                    status + '</span></div>';
                            } else if (data === false || data == 0) {
                                return '<div style="text-align:center;"><span style="background-color: #e09e03 !important;" class="badge bg-warning">' +
                                    status + '</span></div>';
                            } else {
                                return '<div style="text-align:center;"><span style="background-color: #47a102 !important;" class="badge bg-success">Completed</span></div>';
                            }
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'cp_id',
                        name: 'cheque_processing_vendors.id',
                        visible: false // Hide the column but use it for sorting
                    }
                ]
            });
        });

        $('.btn-check').change(function() { //Table Filter
            var selectedOption = $("input[name='btnradio']:checked").attr('id');
            switch (selectedOption) {
                case 'btn-in-progress':
                    $('#myTable').DataTable().ajax.url('{{ route('getinprogresschequeprocessing_vendors') }}')
                        .load();
                    break;
                case 'btn-completed':
                    $('#myTable').DataTable().ajax.url('{{ route('getcompletedchequeprocessing_vendors') }}')
                        .load();
                    break;
                case 'btn-all':
                    $('#myTable').DataTable().ajax.url('{{ route('getchequeprocessing_vendors') }}').load();
                    break;
            }
        });


        window.addEventListener('refresh-table', event => {
            $('#myTable').DataTable().ajax.reload();
        })
    </script>
@endsection
