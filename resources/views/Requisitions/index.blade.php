@extends('layout')

@section('title')
    <title>Requisitions | Requisitions</title>
@endsection

@section('content')
    @livewire('delete-record-modal')
    <div class="card">
        <div class="card-body">

            <div class="d-sm-flex align-items-center justify-content-between mb-7">
                <h1 class="h3 mb-0 text-gray-800" style="margin: auto"><strong><i class="fa-solid fa-file-import"></i> &nbsp;
                        Requisitions</strong></h1>
            </div>
            <div class="row mb-4">
                <!-- Centering the Create Requisition Button -->
                <div class="col-12 text-center">
                    <a href="{{ route('requisitions.create') }}" class="btn btn-primary waves-effect waves-light w-25">
                        <span class="ri-add-circle-line me-1_5"></span>Create Requisition
                    </a>
                </div>
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


            <table id="myTable" class="table table-hover table-bordered mt-5">
                <thead>
                    <tr>
                        <th>Requisition #</th>
                        <th>Vote Number</th>
                        <th>Requesting Unit</th>
                        <th>Assigned To</th>
                        <th style="text-align: center">Status</th>
                        <th style="width: 20%;text-align:center">Actions</th>
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
                "pageLength": 10,
                order: [
                    [5, 'desc']
                ],
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
                        data: 'source_of_funds',
                        name: 'source_of_funds'
                    },
                    {
                        data: 'RequestingUnit',
                        name: 'departments.name'
                    },
                    {
                        data: 'EmployeeName',
                        name: 'users.name'
                    },
                    {
                        data: 'requisition_status',
                        name: 'requisition_status',
                        render: function(data, type, row) {
                            var statusHtml = '';
                            if (data === 'Completed') {
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
                            if (userCanDelete) { // Check if user can delete
                                deleteButton =
                                    '<a class="btn btn-danger" href="#" onclick="showDelete(' + data
                                    .id + ')"><i class="ri-delete-bin-2-line me-1"></i></a>';
                            }
                            return '<div style="text-align:center;"><a class="btn btn-primary" href="/requisitions/view/' +
                                data.id + '" >View</a> ' + deleteButton + '</div>';
                        }
                    },
                    {
                        data: 'id',
                        name: 'id',
                        visible: false // Hide the column but use it for sorting
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


        //Delete records
        function showDelete(id) {
            Livewire.dispatch('show-delete-modal', {
                model: 'Requisition',
                id: id
            });
        }

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
