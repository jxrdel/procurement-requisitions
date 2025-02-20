@extends('layout')

@section('title')
    <title>Dashboard | PRA</title>
@endsection

@section('styles')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .hover-scale {
            transition: transform 0.3s ease;
        }

        .hover-scale:hover {
            transform: scale(1.03);
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-7">
        <h1 class="h3 mb-0 text-gray-800" style="margin: auto"><strong><i class="fa-solid fa-gauge-high"></i> &nbsp;
                Dashboard</strong></h1>
    </div>

    <div id="dashboard" x-data="{
        showCompleted: false,
        showInprogress: false,
    }" x-cloak>
        <div class="row">

            <div class="col">
                <a href="{{ route('requisitions.index') }}">
                    <div class="card bg-primary text-white hover-scale">
                        <div class="card-body">
                            <h5 class="card-title text-white text-center">Total Requisitions</h5>
                            <h4 class="card-text text-center mt-2 text-white">{{ $allRequisitionsCount }}</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="#" @click.prevent="showCompleted = !showCompleted, showInprogress = false">
                    <div class="card bg-success text-white hover-scale">
                        <div class="card-body">
                            <h5 class="card-title text-white text-center">Completed Requisitions</h5>
                            <h4 class="card-text text-center mt-2 text-white">{{ $completedRequisitionsCount }}</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="#" @click.prevent="showInprogress = !showInprogress, showCompleted = false">
                    <div class="card bg-dark text-white hover-scale">
                        <div class="card-body">
                            <h5 class="card-title text-white text-center">In Progress Requisitions</h5>
                            <h4 class="card-text text-center mt-2 text-white">{{ $inprogressRequisitionsCount }}</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col">
                <div class="card bg-danger text-white hover-scale">
                    <div class="card-body">
                        <h5 class="card-title text-white text-center">Average Completion Time</h5>
                        <h4 class="card-text text-center mt-2 text-white">{{ $averageTimeInDays }} day(s)</h4>
                    </div>
                </div>
            </div>

        </div>

        <div x-show="showCompleted" x-transition x-cloak>
            <div class="row mt-5">
                <div class="col">
                    <div class="card" style="box-shadow: 0 4px 8px rgba(14, 236, 14, 0.829);">
                        <div class="card-body">
                            <h5 class="card-title">Completed Requisitions <i
                                    class="ri-checkbox-circle-fill text-success fs-3"></i></h5>
                            <hr>

                            <div>
                                <table id="completedTable" class="table table-hover table-bordered mt-5">
                                    <thead>
                                        <tr>
                                            <th>Requisition #</th>
                                            <th>Vote Number</th>
                                            <th>Requesting Unit</th>
                                            <th>Assigned To</th>
                                            <th style="width: 20%;text-align:center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="inProgressView" x-show="showInprogress" x-transition x-cloak>
            <div class="row mt-5">
                <div class="col">
                    <div class="card" style="box-shadow: 0 4px 8px rgba(230, 196, 106, 0.993);">
                        <div class="card-body">
                            <h5 class="card-title">In Progress Requisitions <i
                                    class="ri-error-warning-fill text-warning fs-3"></i></h5>
                            <hr>

                            <table id="inprogressTable" class="table table-hover table-bordered mt-5">
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
                </div>
            </div>

        </div>

    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>


    <script>
        //     // Get the grouped requisition data from Laravel
        //     var inProgressDonutData = Object.values(@json($inProgressDonutData));

        //     // Use map to extract the labels (statuses) and data (counts)
        //     var labels = inProgressDonutData.map(item => item.status);
        //     var dataValues = inProgressDonutData.map(item => item.count);
        //   
    </script>

    //
    <script>
        //     // Get the grouped requisition data from Laravel
        //     var inProgressDonutData = Object.values(@json($inProgressDonutData));

        //     // Use map to extract the labels (statuses) and data (counts)
        //     var labels = inProgressDonutData.map(item => item.status);
        //     var dataValues = inProgressDonutData.map(item => item.count);

        //     // Calculate total count to display percentages
        //     var totalCount = dataValues.reduce((a, b) => a + b, 0);
        //   
    </script>

    //
    <script>
        //     var ctx = document.getElementById('myDonutChart').getContext('2d');

        //     const data = {
        //         labels: labels,  // Use the dynamic labels
        //         datasets: [{
        //             label: 'Amount',
        //             data: dataValues,  // Dynamic data values
        //             backgroundColor: [
        //                 'rgb(255, 99, 132)',
        //                 'rgb(54, 162, 235)',
        //                 'rgb(255, 205, 86)',
        //                 'rgb(75, 192, 192)',
        //                 'rgb(153, 102, 255)'
        //             ],
        //             hoverOffset: 4
        //         }]
        //     };

        //     var myDonutChart = new Chart(ctx, {
        //     type: 'doughnut',
        //     data: data,
        //     options: {
        //         plugins: {
        //             legend: {
        //                 position: 'right',  // Move the legend to the right
        //             },
        //             datalabels: {
        //                 formatter: (value, ctx) => {
        //                     // Calculate percentage without decimals
        //                     let percentage = Math.round((value / totalCount * 100)) + '%';
        //                     return percentage;
        //                 },
        //                 color: '#fff',  // Label color (white)
        //                 font: {
        //                     weight: 'bold'
        //                 }
        //             }
        //         },
        //         cutout: '60%', // Adjust the cutout to make the donut line thinner
        //     },
        //     plugins: [ChartDataLabels]  // Activate the datalabels plugin
        // });

        $(document).ready(function() {
            $('#completedTable').DataTable({
                "pageLength": 10,
                order: [
                    [5, 'desc']
                ], // Sorting by the hidden `created_at` column (index 5)
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('getcompletedrequisitions') }}",
                    "type": "GET"
                },
                "columns": [{
                        data: 'requisition_no',
                        name: 'requisition_no'
                    },
                    {
                        data: 'source_of_funds',
                        name: 'requisitions.source_of_funds'
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
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return '<div style="text-align:center;"><a class="btn btn-primary" href="/requisitions/view/' +
                                data.id +
                                '" >View</a></div>';
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

        $(document).ready(function() {
            $('#inprogressTable').DataTable({
                "pageLength": 10,
                order: [
                    [5, 'desc']
                ], // Sorting by the hidden `created_at` column (index 5)
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('getinprogressrequisitions') }}",
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
                                statusHtml = data;
                                statusHtml =
                                    '<span style="background-color: #e09e03 !important;" class="badge bg-success">' +
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
                            return '<div style="text-align:center;"><a class="btn btn-primary" href="/requisitions/view/' +
                                data.id +
                                '" >View</a></div>';
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
    </script>
@endsection
