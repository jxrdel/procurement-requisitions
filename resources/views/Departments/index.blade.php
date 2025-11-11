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
                            <th style="text-align: center;width:20%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($departments as $department)
                            <tr>
                                <td>{{ $department->name }}</td>
                                <td style="text-align: center">
                                    <a class="btn btn-primary" href="javascript:void(0);"
                                        onclick="showEdit({{ $department->id }}, '{{ $department->name }}', {{ $department->head_of_department_id ?? 'null' }})">Edit</a>
                                </td>
                            </tr>
                        @endforeach
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
                <form id="editDepartmentForm" method="POST">
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
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
                ]
            });
        });

        function showEdit(id, name, head_of_department_id) {
            $('#editDepartmentForm').attr('action', '/departments/' + id);
            $('#edit_name').val(name);
            $('#edit_head_of_department_id').val(head_of_department_id);
            $('#editDepartmentModal').modal('show');
        }
    </script>
@endsection
