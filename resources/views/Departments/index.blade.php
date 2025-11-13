@extends('layout')

@section('title')
    <title>Departments | PRA</title>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-sm-flex align-items-center justify-content-between mb-7">
                <h1 class="h3 mb-0 text-gray-800" style="margin: auto"><strong><i class="fa-solid fa-building"></i> &nbsp;
                        Departments</strong></h1>
            </div>

            <div class="row mt-5">
                <table id="myTable" class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Head of Department</th>
                            <th style="text-align: center;width:20%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Department Modal -->
    <div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-labelledby="editDepartmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDepartmentModalLabel">Edit Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editDepartmentForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_head_of_department_id" class="form-label">Head of Department</label>
                            <select class="form-select" id="edit_head_of_department_id" name="head_of_department_id">
                                <option value="">Select Head of Department</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#myTable').DataTable({
                "pageLength": 25,
                order: [
                    [0, 'asc']
                ],
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('departments.index') }}",
                    "type": "GET"
                },
                "columns": [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'head_of_department',
                        name: 'headOfDepartment.name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $('#editDepartmentForm').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var url = form.attr('action');

                // Clear existing error messages
                $('.text-danger').remove();

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        $('#editDepartmentModal').modal('hide');
                        table.ajax.reload();
                        toastr.options = {
                            "progressBar": true,
                            "closeButton": true,
                        };
                        toastr.success('Department updated successfully!', '', {
                            timeOut: 3000
                        });
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('#edit_' + key).after('<span class="text-danger">' +
                                    value[
                                        0] + '</span>');
                            });
                        }
                    }
                });
            });
        });

        function showEdit(id, name, head_of_department_id) {
            $('#editDepartmentForm').attr('action', '/departments/' + id);
            $('#edit_name').val(name);
            if (head_of_department_id === 'null') {
                $('#edit_head_of_department_id').val('');
            } else {
                $('#edit_head_of_department_id').val(head_of_department_id);
            }
            $('#editDepartmentModal').modal('show');
        }
    </script>
@endsection
