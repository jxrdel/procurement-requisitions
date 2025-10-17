@extends('layout')

@section('title')
    <title>Requisition Forms | PRA</title>
@endsection

@section('content')
    @livewire('delete-record-modal')
    @livewire('change-financial-year')
    @livewire('view-status-modal')
    <div class="card">
        <div class="card-body">

            <div class="d-sm-flex align-items-center justify-content-between mb-7">
                <h1 class="h3 mb-0 text-gray-800" style="margin: auto"><strong><i class="fa-solid fa-file-import"></i> &nbsp;
                        Requisition Forms</strong></h1>
            </div>

            <div class="row mb-4 align-items-center">

                <!-- Centered Create Requisition Button -->
                <div class="col d-flex justify-content-center">
                    <a href="{{ route('requisition_forms.create') }}" class="btn btn-primary waves-effect waves-light">
                        <span class="ri-add-circle-line me-1_5"></span>Create Requisition Form
                    </a>
                </div>
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
                        <th style="width: 30%; text-align: center;">Contact Person</th>
                        <th style="width: 25%; text-align: center;">Date Created</th>
                        <th style="width: 25%; text-align: center;">Status</th>
                        <th style="width: 15%; text-align: center;">Actions</th>
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
                        "data": "contact_person_name",
                        "name": "contact_person_id",
                        "title": "Contact Person"
                    },
                    {
                        "data": "date_created_formatted",
                        "name": "created_at",
                        "title": "Date Created"
                    },
                    {
                        "data": "status_badge",
                        "name": "status",
                        "title": "Status"
                    },
                    {
                        "data": "actions",
                        "name": "actions",
                        "title": "Actions",
                        "orderable": false,
                        "searchable": false
                    }
                ],
                "order": [
                    [2, "desc"]
                ] // Default sort by Date Created (index 2) descending
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
