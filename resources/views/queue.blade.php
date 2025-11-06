@extends('layout')

@section('title')
    <title>Queue | PRA</title>
@endsection

@section('content')
    @livewire('delete-record-modal')
    @livewire('change-financial-year')
    @livewire('view-status-modal')
    <div class="card">
        <div class="card-body">

            <div class="d-sm-flex align-items-center justify-content-between mb-7">
                <h1 class="h3 mb-0 text-gray-800" style="margin: auto"><strong><i class="fa-solid fa-list-ol"></i> &nbsp;
                        Queue</strong></h1>
            </div>


            {{-- <div class="row mb-4">
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
            </div> --}}


            <table id="myTable" class="table table-hover table-bordered mt-5">
                <thead>
                    <tr>
                        <th style="width: 15%; text-align: center;">Form Code</th>
                        <th style="width: 25%; text-align: center;">Items</th>
                        <th style="width: 20%; text-align: center;">Date Created</th>
                        <th style="width: 15%; text-align: center;">Form Status</th>
                        <th style="width: 15%; text-align: center;">Requisition Status</th>
                        <th style="width: 10%; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    {{-- DataTables will populate this --}}
                </tbody>
            </table>

        </div>
    </div>
@endsection


@section('scripts')
    <script>
        // Ensure you load jQuery and DataTables libraries before this script
        $(document).ready(function() {
            $('#myTable').DataTable({
                "pageLength": 100,
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('getrequisition_forms') }}",
                    "type": "GET"
                },
                "columns": [{
                        "data": "form_code",
                        "name": "form_code",
                        "title": "Form Code"
                    },
                    {
                        "data": "items_list",
                        "name": "items_list",
                        "title": "Items"
                    },
                    {
                        "data": "date_created_formatted",
                        "name": "created_at",
                        "title": "Date Created"
                    },
                    {
                        "data": "status_badge",
                        "name": "status",
                        "title": "Form Status"
                    },
                    {
                        "data": "requisition_status_badge",
                        "name": "requisition.status",
                        "title": "Requisition Status",
                        "orderable": false,
                        "searchable": true,
                        "className": "text-center"
                    },
                    {
                        "data": "actions",
                        "name": "actions",
                        "title": "Actions",
                        "orderable": false,
                        "searchable": false
                    },
                ],
                "order": [
                    [2, "desc"]
                ]
            });
        });

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
