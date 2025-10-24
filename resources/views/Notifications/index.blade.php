@extends('layout')

@section('title')
    <title>Notifications | PRA</title>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">

            <div class="d-sm-flex align-items-center justify-content-between mb-7">
                <h1 class="h3 mb-0 text-gray-800" style="margin: auto">
                    <strong><i class="ri-notification-3-line"></i> &nbsp; Notifications</strong>
                </h1>
            </div>

            <div class="row mt-5">
                <table id="notificationsTable" class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th style="text-align: center; width: 15%">Actions</th>
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
            $('#notificationsTable').DataTable({
                "pageLength": 25,
                "processing": true,
                "serverSide": false, // Changed to false for collection-based data
                "order": [
                    [2, 'desc'] // Order by date descending (newest first)
                ],
                "ajax": {
                    "url": "{{ route('notifications.get') }}",
                    "type": "GET",
                    "dataSrc": "data"
                },
                "columns": [{
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'message',
                        name: 'message'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        type: 'date'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endsection
