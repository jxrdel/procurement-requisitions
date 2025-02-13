@extends('layout')

@section('title')
    <title>Votes | PRA</title>
@endsection

@section('content')
    @livewire('create-vote-modal')
    @livewire('edit-vote-modal')
    @livewire('delete-record-modal')
    <div class="card">
        <div class="card-body">

            <div class="d-sm-flex align-items-center justify-content-between mb-7">
                <h1 class="h3 mb-0 text-gray-800" style="margin: auto"><strong><i class="fa-solid fa-check-to-slot"></i> &nbsp;
                        Votes</strong></h1>
            </div>

            <div class="row">
                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#createVoteModal"
                    class="btn btn-primary waves-effect waves-light w-25 m-auto">
                    <span class="tf-icons ri-add-circle-line me-1_5"></span>Create Vote
                </a>
            </div>

            <div class="row mt-5">

                <table id="myTable" class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Vote Number</th>
                            <th>Active</th>
                            <th style="text-align: center;width:20%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>

        </div>
    </div>
@endsection


@section('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js'></script>

    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                "pageLength": 25,
                order: [
                    [0, 'asc']
                ],
                "processing": true,
                "ajax": {
                    "url": "{{ route('getvotes') }}",
                    "type": "GET"
                },
                "columns": [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'number',
                        name: 'number'
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            // If vote is active display a checkmark
                            if (data.is_active == 1) {
                                return '<div style="text-align:center"><i class="ri-check-fill" style="color:green"></i></div>';
                            } else {
                                return '<div style="text-align:center"><i class="ri-close-fill" style="color:red"></i></div>';
                            }
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return ' <div style="text-align:center"><a class="btn btn-primary" href="javascript:void(0);" onclick="showEdit(' +
                                data.id +
                                ')">Edit</a>  <a class="btn btn-danger" href="javascript:void(0);" onclick="showDelete(' +
                                data.id + ')"><i class="ri-delete-bin-2-line"></i></a></div>';
                        }
                    },
                ]
            });
        });

        window.addEventListener('refresh-table', event => {
            $('#myTable').DataTable().ajax.reload();
        })


        window.addEventListener('close-create-modal', event => {
            $('#createVoteModal').modal('hide');
        })

        function showEdit(id) {
            Livewire.dispatch('show-edit-modal', {
                id: id
            });
        }

        window.addEventListener('display-edit-modal', event => {
            $('#editVoteModal').modal('show');
        })

        window.addEventListener('close-edit-modal', event => {
            $('#editVoteModal').modal('hide');
        })


        function showDelete(id) {
            Livewire.dispatch('show-delete-modal', {
                model: 'Vote',
                id: id
            });
        }

        window.addEventListener('display-delete-modal', event => {
            $('#deleteRecordModal').modal('show');
        })

        window.addEventListener('close-delete-modal', event => {
            $('#deleteRecordModal').modal('hide');
        })
    </script>
@endsection
