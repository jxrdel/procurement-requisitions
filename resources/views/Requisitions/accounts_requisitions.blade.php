@extends('layout')

@section('title')
    <title>Requisitions | Requisitions</title>
@endsection

@section('content')
    <div class="card">
      <div class="card-body">
        
        <div class="d-sm-flex align-items-center justify-content-between mb-7">
            <h1 class="h3 mb-0 text-gray-800" style="margin: auto"><strong><i class="fa-solid fa-file-import"></i> &nbsp;Accounts Requisitions</strong></h1>
        </div>

        
        <table id="myTable" class="table table-hover table-bordered">
          <thead>
          <tr>
            <th>Requisition #</th>
            <th>Requesting Unit</th>
            <th>Item</th>
            <th style="width: 20%">Actions</th>
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
            order: [[0, 'desc']],
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('getaccounts_requisitions') }}",
                "type": "GET"
            },
            "columns": [
                { data: 'requisition_no', name: 'requisition_no' },
                { data: 'RequestingUnit', name: 'departments.name' }, // Correct field for searching
                { data: 'ItemName', name: 'requisitions.item' },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return ' <div style="text-align:center"><a class="btn btn-primary" href="/accounts_requisitions/view/' + data.ar_id + '" ><i class="ri-eye-line me-1"></i></a> </div>';
                    }
                },
            ]
        });
    });



      window.addEventListener('refresh-table', event => {
          $('#myTable').DataTable().ajax.reload();
      })
              
  </script>
@endsection