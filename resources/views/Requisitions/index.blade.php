@extends('layout')

@section('title')
    <title>Requisitions | PRA</title>
@endsection

@section('content')
    @livewire('delete-record-modal')
    @livewire('change-financial-year')
    @livewire('view-status-modal')
    <div class="card">
        <div class="card-body">

            <div class="d-sm-flex align-items-center justify-content-between mb-7">
                <h1 class="h3 mb-0 text-gray-800" style="margin: auto"><strong><i class="fa-solid fa-file-import"></i> &nbsp;
                        Requisitions</strong></h1>
            </div>

            @can('create-requisitions')
                <div class="row mb-4 align-items-center">
                    <!-- Empty Column for Spacing -->
                    <div class="col"></div>

                    <!-- Centered Create Requisition Button -->
                    <div class="col d-flex justify-content-center">
                        <a href="{{ route('requisitions.create') }}" class="btn btn-primary waves-effect waves-light">
                            <span class="ri-add-circle-line me-1_5"></span>Create Requisition
                        </a>
                    </div>

                    <!-- Right-aligned Change Financial Year Button -->
                    <div class="col d-flex justify-content-end">
                        @can('change-financial-year')
                            <a href="javascript:void(0);" class="btn btn-dark waves-effect waves-light" data-bs-toggle="modal"
                                data-bs-target="#editFYModal">
                                Change Financial Year
                            </a>
                        @endcan
                    </div>
                </div>
            @endcan

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


            <table id="myTable" class="table table-hover table-bordered mt-5">
                <thead>
                    <tr>
                        <th style="width: 12%">Requisition #</th>
                        {{-- <th>Vote Number</th> --}}
                        <th>Requesting Unit</th>
                        <th>Item</th>
                        <th style="text-align: center">Status</th>
                        <th style="width: 15%;text-align:center">Actions</th>
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
        var userCanDelete =
            @can('delete-records')
                true
            @else
                false
            @endcan ;

        $(document).ready(function() {
            $('#myTable').DataTable({
                "pageLength": 100,
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('getrequisitions') }}",
                    "type": "GET"
                },
                "columns": [{
                        data: 'requisition_no',
                        name: 'requisition_no'
                    },
                    {
                        data: 'RequestingUnit',
                        name: 'departments.name'
                    },
                    {
                        data: 'item',
                        name: 'item'
                    },
                    {
                        data: 'vendor_status',
                        name: 'vendor_status',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            var statusHtml = '';

                            if (data === 'Complex Status') {
                                statusHtml =
                                    '<a class="btn btn-dark" href="javascript:void(0);" onclick="showStatus(' +
                                    row
                                    .id + ')">' +
                                    '<i class="fa-solid fa-asterisk"></i></a>';;
                            } else if (data === 'Completed') {
                                statusHtml =
                                    '<span style="background-color: #47a102 !important;" class="badge bg-success">Completed</span>';
                            } else {
                                statusHtml =
                                    '<span style="background-color: #e09e03 !important;" class="badge bg-warning">' +
                                    data + '</span>';
                            }
                            return '<div style="text-align:center;">' + statusHtml + '</div>';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            var deleteButton = '';

                            if (userCanDelete) {
                                deleteButton =
                                    '<a class="btn btn-danger" href="javascript:void(0);" onclick="showDelete(' +
                                    data
                                    .id + ')">' +
                                    '<i class="ri-delete-bin-2-line"></i></a>';
                            }

                            return '<div style="text-align:center;">' +
                                '<a class="btn btn-primary" href="/requisitions/view/' + data.id +
                                '">View</a> ' +
                                deleteButton +
                                '</div>';
                        }
                    },
                    {
                        data: 'id',
                        name: 'id',
                        visible: false // Hidden but usable for sorting
                    }
                ]
            });
        });




        window.addEventListener('refresh-table', event => {
            $('#myTable').DataTable().ajax.reload();
        })

        window.addEventListener('close-create-modal', event => {
            $('#createContactModal').modal('hide');
        })


        window.addEventListener('close-fy-modal', event => {
            $('#editFYModal').modal('hide');
        })

        //Delete records
        function showDelete(id) {
            Livewire.dispatch('show-delete-modal', {
                model: 'Requisition',
                id: id
            });
        }

        //Show status
        function showStatus(id) {
            Livewire.dispatch('show-status-modal', {
                id: id
            });
        }

        window.addEventListener('display-status-modal', event => {
            $('#viewStatusModal').modal('show');
        })

        window.addEventListener('display-delete-modal', event => {
            $('#deleteRecordModal').modal('show');
        })

        window.addEventListener('close-delete-modal', event => {
            $('#deleteRecordModal').modal('hide');
        })

        $('.btn-check').change(function() { //Table Filter
            var selectedOption = $("input[name='btnradio']:checked").attr('id');
            switch (selectedOption) {
                case 'btn-in-progress':
                    $('#myTable').DataTable().ajax.url('{{ route('getinprogressrequisitions') }}').load();
                    break;
                case 'btn-completed':
                    $('#myTable').DataTable().ajax.url('{{ route('getcompletedrequisitions') }}').load();
                    break;
                case 'btn-all':
                    $('#myTable').DataTable().ajax.url('{{ route('getrequisitions') }}').load();
                    break;
            }
        });
    </script>
@endsection
