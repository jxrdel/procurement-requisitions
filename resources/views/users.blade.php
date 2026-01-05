@extends('layout')

@section('title')
    <title>Users | PRA</title>
@endsection

@section('styles')
    <style>
        /* Custom styles for Select2 */
        .select2-container--default .select2-selection--single {
            min-height: 38px;
            /* Adjust as needed */
            height: 38px;
            padding: 6px 12px;
            /* Standard input padding */
            display: flex;
            align-items: center;
            /* Vertically center content */
            border-color: #d9dee3;
            /* Match existing border color */
        }

        .select2-container--default .select2-selection__arrow {
            height: 36px;
            /* Match the height of the select box */
            top: 1px;
            right: 1px;
        }

        /* Adjust for the rendered text inside select2 */
        .select2-container--default .select2-selection__rendered {
            line-height: 24px;
            /* Adjust line height to vertically center text */
            padding-left: 0;
            /* Remove default padding as it's handled by parent */
            padding-right: 0;
        }

        .select2-container--default .select2-selection--single .select2-selection__clear {
            padding-right: 6px;
        }
    </style>
@endsection

@section('content')
    @livewire('create-user-modal')
    @livewire('edit-user-modal')
    @livewire('delete-record-modal')
    <div class="card">
        <div class="card-body">

            <div class="d-sm-flex align-items-center justify-content-between mb-7">
                <h1 class="h3 mb-0 text-gray-800" style="margin: auto"><strong><i class="fa-solid fa-users"></i> &nbsp;
                        Users</strong></h1>
            </div>

            <div class="row">
                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#createUserModal"
                    class="btn btn-primary waves-effect waves-light w-25 m-auto">
                    <span class="tf-icons ri-user-add-fill me-1_5"></span>Create User
                </a>
            </div>

            <div class="row mt-5">

                <table id="myTable" class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Department</th>
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
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                "pageLength": 25,
                order: [
                    [0, 'asc']
                ],
                "processing": true,
                "ajax": {
                    "url": "{{ route('getusers') }}",
                    "type": "GET"
                },
                "columns": [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'department_name',
                        name: 'department.name',
                        title: 'Department'
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
            $('#createUserModal').modal('hide');
        })

        function showEdit(id) {
            Livewire.dispatch('show-edit-modal', {
                id: id
            });
        }

        window.addEventListener('display-edit-modal', event => {
            $('#editUserModal').modal('show');
        })

        window.addEventListener('close-edit-modal', event => {
            $('#editUserModal').modal('hide');
        })


        function showDelete(id) {
            Livewire.dispatch('show-delete-modal', {
                model: 'User',
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
