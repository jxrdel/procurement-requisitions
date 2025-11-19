<?php

namespace App\Http\Controllers;

use App\Models\CBRequisition;
use App\Models\Requisition;
use App\Models\RequisitionRequestForm;
use App\Models\User;
use App\Models\Vote;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class Controller
{
    public function index()
    {
        if (Auth::user()->role->name !== 'Super Admin' && Auth::user()->department->name !== 'Office of the Permanent Secretary' && Auth::user()->department->name !== 'Procurement Unit') {
            //Redirect users to their department dashboards
            if (Auth::user()->department->name === 'Cost & Budgeting') {
                return redirect()->route('queue');
            } elseif (Auth::user()->department->name === 'Vote Control') {
                return redirect()->route('vote_control.index');
            } elseif (Auth::user()->department->name === 'Check Staff') {
                return redirect()->route('check_room.index');
            } elseif (Auth::user()->department->name === 'Cheque Processing') {
                return redirect()->route('cheque_processing.index');
            } elseif (Auth::user()->department->name === 'Accounts Payable') {
                return redirect()->route('accounts_payable.index');
            } else {
                return redirect()->route('requisition_forms.index');
            }
        }

        //Count of all requisitions
        $allRequisitionsCount = Requisition::count();

        //Count of completed requisitions
        $completedRequisitionsCount = Requisition::where('is_completed', true)->count();

        //Count of requisitions in progress
        $inprogressRequisitionsCount = Requisition::where('is_completed', false)->count();

        //If the user is a Viewer and not in Procurement or Office of the Permanent Secretary, only show their department's requisitions i.e. For Jesse's account
        if (Auth::user()->role->name === 'Viewer' && Auth::user()->department->name !== 'Procurement Unit' && Auth::user()->department->name !== 'Office of the Permanent Secretary') {
            $allRequisitionsCount = Requisition::join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
                ->where('departments.name', Auth::user()->department->name)
                ->count();

            $completedRequisitionsCount = Requisition::where('is_completed', true)
                ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
                ->where('departments.name', Auth::user()->department->name)
                ->count();

            $inprogressRequisitionsCount = Requisition::where('is_completed', false)
                ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
                ->where('departments.name', Auth::user()->department->name)
                ->count();
        }

        $inProgressDonut = Requisition::select('requisition_status', 'requisition_no')
            ->where('is_completed', false)
            ->get()
            ->groupBy('requisition_status')
            ->map(function ($group) {
                return [
                    'status' => $group->first()->requisition_status, // The requisition status name
                    'count' => $group->count() // Count of requisitions with that status
                ];
            });

        $inProgress = Requisition::with('procurement_officer')
            ->where('is_completed', false)
            ->get()
            ->map(function ($requisition) {
                return [
                    'item_name' => $requisition->item,
                    'officer_name' => $requisition->procurement_officer->name ?? 'Not Assigned',
                    'time_elapsed' => Carbon::parse($requisition->date_received_procurement)->diffForHumans(),
                ];
            });

        $completedRequisitions = Requisition::with('vote_control_requisition')
            ->where('is_completed', true)
            ->get();
        // Calculate total time difference in days
        $totalTimeInDays = $completedRequisitions->reduce(function ($carry, $requisition) {
            $date_received_procurement = Carbon::parse($requisition->date_received_procurement);
            $completed_at = Carbon::parse($requisition->date_completed);
            $timeDifferenceInDays = $date_received_procurement->diffInDays($completed_at); // Get the difference in days
            return $carry + $timeDifferenceInDays;
        }, 0);

        if ($completedRequisitions->count() == 0) {
            $averageTimeInDays = 0;
        } else {
            // Calculate the average time in days
            $averageTimeInDays = $totalTimeInDays / $completedRequisitions->count();
        }


        //Round days to nearest whole number
        $averageTimeInDays = round($averageTimeInDays);


        return view('dashboard', [
            'allRequisitionsCount' => $allRequisitionsCount,
            'completedRequisitionsCount' => $completedRequisitionsCount,
            'inprogressRequisitionsCount' => $inprogressRequisitionsCount,
            'averageTimeInDays' => $averageTimeInDays,
            'inProgressDonutData' => $inProgressDonut,
            'inProgressData' => $inProgress
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function users()
    {
        if (Gate::denies('view-users-page')) {
            return redirect()->route('/')->with('error', 'You are not authorized to view this page');
        }

        return view('users');
    }

    public function getUsers()
    {
        $users = User::with('department')->select('users.*');

        return DataTables::of($users)
            ->addColumn('department_name', function ($user) {
                return $user->department?->name ?? 'N/A';
            })
            ->filterColumn('department_name', function (Builder $query, $keyword) {
                $query->whereHas('department', function (Builder $q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->make(true);
    }


    public function help()
    {
        return view('help');
    }

    public function votes()
    {
        return view('votes');
    }

    public function getVotes()
    {
        $votes = Vote::all();

        return DataTables::of($votes)->make(true);
    }

    public function queue()
    {
        if (Gate::denies('view-queue-page')) {
            abort(403);
        }
        return view('queue');
    }

    public function getQueue()
    {
        $user = Auth::user();
        $departmentName = $user->department->name;
        $query = collect();

        if ($user->role->name === 'Super Admin') {
            $forms = RequisitionRequestForm::all()->map(function ($item) {
                $status = $item->status === 'Completed'
                    ? '<div style="text-align:center;"><span style="background-color: #8bc34a !important;" class="badge bg-success">Completed</span></div>'
                    : '<div style="text-align:center;"><span style="background-color: #e09e03 !important;" class="badge bg-warning">' . $item->status . '</span></div>';

                return [
                    'id' => $item->id,
                    'item_type' => 'Requisition Form',
                    'item_code' => $item->form_code,
                    'requesting_unit' => $item->requestingUnit->name,
                    'item' => $item->items->pluck('name')->implode(', '),
                    'date_received' => $item->created_at,
                    'status' => $status,
                ];
            });
            $query = $query->merge($forms);

            $requisitions = CBRequisition::with('requisition')->get()->map(function ($item) {
                $status = $item->requisition->requisition_status === 'Completed'
                    ? '<div style="text-align:center;"><span style="background-color: #8bc34a !important;" class="badge bg-success">Completed</span></div>'
                    : '<div style="text-align:center;"><span style="background-color: #e09e03 !important;" class="badge bg-warning">' . $item->requisition->requisition_status . '</span></div>';

                return [
                    'id' => $item->id,
                    'item_type' => 'Requisition',
                    'item_code' => $item->requisition->requisition_no,
                    'requesting_unit' => $item->requisition->department->name,
                    'item' => $item->requisition->item,
                    'date_received' => $item->date_received,
                    'status' => $status,
                ];
            });
            $query = $query->merge($requisitions);
        } else {
            if ($departmentName === 'Cost & Budgeting') {
                $forms = RequisitionRequestForm::where('sent_to_cab', true)->get()->map(function ($item) {
                    $status = $item->completed_by_cab === true
                        ? '<div style="text-align:center;"><span style="background-color: #8bc34a !important;" class="badge bg-success">Completed</span></div>'
                        : '<div style="text-align:center;"><span style="background-color: #e09e03 !important;" class="badge bg-warning">' . $item->status . '</span></div>';

                    return [
                        'id' => $item->id,
                        'item_type' => 'Requisition Form',
                        'item_code' => $item->form_code,
                        'requesting_unit' => $item->requestingUnit->name,
                        'item' => $item->items->pluck('name')->implode(', '),
                        'date_received' => $item->date_sent_to_cab,
                        'status' => $status,
                    ];
                });
                $query = $query->merge($forms);

                $requisitions = CBRequisition::where('is_completed', false)->with('requisition')->get()->map(function ($item) {
                    $status = $item->is_completed == true
                        ? '<div style="text-align:center;"><span style="background-color: #8bc34a !important;" class="badge bg-success">Completed</span></div>'
                        : '<div style="text-align:center;"><span style="background-color: #e09e03 !important;" class="badge bg-warning">' . $item->requisition->requisition_status . '</span></div>';

                    return [
                        'id' => $item->id,
                        'item_type' => 'Requisition',
                        'item_code' => $item->requisition->requisition_no,
                        'requesting_unit' => $item->requisition->department->name,
                        'item' => $item->requisition->item,
                        'date_received' => $item->date_received,
                        'status' => $status,
                    ];
                });
                $query = $query->merge($requisitions);
            } elseif ($departmentName === 'Office of the Permanent Secretary') {
                $forms = RequisitionRequestForm::where('sent_to_ps', true)->get()->map(function ($item) {
                    $status = $item->status === 'Completed'
                        ? '<div style="text-align:center;"><span style="background-color: #8bc34a !important;" class="badge bg-success">Completed</span></div>'
                        : '<div style="text-align:center;"><span style="background-color: #e09e03 !important;" class="badge bg-warning">' . $item->status . '</span></div>';

                    return [
                        'id' => $item->id,
                        'item_type' => 'Requisition Form',
                        'item_code' => $item->form_code,
                        'requesting_unit' => $item->requestingUnit->name,
                        'item' => $item->items->pluck('name')->implode(', '),
                        'date_received' => $item->reporting_officer_approval_date,
                        'status' => $status,
                    ];
                });
                $query = $query->merge($forms);
            } elseif ($departmentName === 'Office of the Deputy Permanent Secretary') {
                $forms = RequisitionRequestForm::where('sent_to_dps', true)->get()->map(function ($item) {
                    $status = $item->status === 'Completed'
                        ? '<div style="text-align:center;"><span style="background-color: #8bc34a !important;" class="badge bg-success">Completed</span></div>'
                        : '<div style="text-align:center;"><span style="background-color: #e09e03 !important;" class="badge bg-warning">' . $item->status . '</span></div>';

                    return [
                        'id' => $item->id,
                        'item_type' => 'Requisition Form',
                        'item_code' => $item->form_code,
                        'requesting_unit' => $item->requestingUnit->name,
                        'item' => $item->items->pluck('name')->implode(', '),
                        'date_received' => $item->reporting_officer_approval_date,
                        'status' => $status,
                    ];
                });
                $query = $query->merge($forms);
            } elseif ($departmentName === 'Office of the Chief Medical Officer') {
                $forms = RequisitionRequestForm::where('sent_to_cmo', true)->get()->map(function ($item) {
                    $status = $item->status === 'Completed'
                        ? '<div style="text-align:center;"><span style="background-color: #8bc34a !important;" class="badge bg-success">Completed</span></div>'
                        : '<div style="text-align:center;"><span style="background-color: #e09e03 !important;" class="badge bg-warning">' . $item->status . '</span></div>';

                    return [
                        'id' => $item->id,
                        'item_type' => 'Requisition Form',
                        'item_code' => $item->form_code,
                        'requesting_unit' => $item->requestingUnit->name,
                        'item' => $item->items->pluck('name')->implode(', '),
                        'date_received' => $item->reporting_officer_approval_date,
                        'status' => $status,
                    ];
                });
                $query = $query->merge($forms);
            } elseif ($departmentName === 'Procurement Unit') {
                $forms = RequisitionRequestForm::where('reporting_officer_approval', true)->get()->map(function ($item) {
                    $status = $item->status === 'Completed'
                        ? '<div style="text-align:center;"><span style="background-color: #8bc34a !important;" class="badge bg-success">Completed</span></div>'
                        : '<div style="text-align:center;"><span style="background-color: #e09e03 !important;" class="badge bg-warning">' . $item->status . '</span></div>';

                    return [
                        'id' => $item->id,
                        'item_type' => 'Requisition Form',
                        'item_code' => $item->form_code,
                        'requesting_unit' => $item->requestingUnit->name,
                        'item' => $item->items->pluck('name')->implode(', '),
                        'date_received' => $item->reporting_officer_approval_date,
                        'status' => $status,
                    ];
                });
                $query = $query->merge($forms);
            }
        }

        return DataTables::of($query->sortByDesc('date_received'))
            ->addColumn('view', function ($row) use ($departmentName) {
                $url = '';
                if ($row['item_type'] === 'Requisition Form') {
                    $url = route('queue.form.view', $row['id']);
                } else {
                    if ($departmentName === 'Cost & Budgeting') {
                        $url = route('cost_and_budgeting.view', $row['id']);
                    } else {
                        $url = route('queue.requisition.view', $row['id']);
                    }
                }
                return '<div style="text-align:center;"><a href="' . $url . '" class="btn btn-primary btn-sm">View</a></div>';
            })
            ->rawColumns(['status', 'view'])
            ->make(true);
    }

    public function getInProgressQueue()
    {
        $user = Auth::user();
        $departmentName = $user->department->name;
        $query = collect();

        if ($user->role->name === 'Super Admin') {
            $forms = RequisitionRequestForm::where('status', '!=', 'Completed')->get()->map(function ($item) {
                $status = $item->status === 'Completed'
                    ? '<div style="text-align:center;"><span style="background-color: #8bc34a !important;" class="badge bg-success">Completed</span></div>'
                    : '<div style="text-align:center;"><span style="background-color: #e09e03 !important;" class="badge bg-warning">' . $item->status . '</span></div>';

                return [
                    'id' => $item->id,
                    'item_type' => 'Requisition Form',
                    'item_code' => $item->form_code,
                    'requesting_unit' => $item->requestingUnit->name,
                    'item' => $item->items->pluck('name')->implode(', '),
                    'date_received' => $item->created_at,
                    'status' => $status,
                ];
            });
            $query = $query->merge($forms);

            $requisitions = CBRequisition::where('is_completed', false)->with('requisition')->get()->map(function ($item) {
                $status = $item->requisition->requisition_status === 'Completed'
                    ? '<div style="text-align:center;"><span style="background-color: #8bc34a !important;" class="badge bg-success">Completed</span></div>'
                    : '<div style="text-align:center;"><span style="background-color: #e09e03 !important;" class="badge bg-warning">' . $item->requisition->requisition_status . '</span></div>';

                return [
                    'id' => $item->id,
                    'item_type' => 'Requisition',
                    'item_code' => $item->requisition->requisition_no,
                    'requesting_unit' => $item->requisition->department->name,
                    'item' => $item->requisition->item,
                    'date_received' => $item->date_received,
                    'status' => $status,
                ];
            });
            $query = $query->merge($requisitions);
        } else {
            if ($departmentName === 'Cost & Budgeting') {
                $forms = RequisitionRequestForm::where('sent_to_cab', true)->where('completed_by_cab', false)->get()->map(function ($item) {
                    $status = $item->completed_by_cab == true
                        ? '<div style="text-align:center;"><span style="background-color: #8bc34a !important;" class="badge bg-success">Completed</span></div>'
                        : '<div style="text-align:center;"><span style="background-color: #e09e03 !important;" class="badge bg-warning">' . $item->status . '</span></div>';

                    return [
                        'id' => $item->id,
                        'item_type' => 'Requisition Form',
                        'item_code' => $item->form_code,
                        'requesting_unit' => $item->requestingUnit->name,
                        'item' => $item->items->pluck('name')->implode(', '),
                        'date_received' => $item->date_sent_to_cab,
                        'status' => $status,
                    ];
                });
                $query = $query->merge($forms);

                $requisitions = CBRequisition::where('is_completed', false)->with('requisition')->get()->map(function ($item) {
                    $status = $item->is_completed == true
                        ? '<div style="text-align:center;"><span style="background-color: #8bc34a !important;" class="badge bg-success">Completed</span></div>'
                        : '<div style="text-align:center;"><span style="background-color: #e09e03 !important;" class="badge bg-warning">' . $item->requisition->requisition_status . '</span></div>';

                    return [
                        'id' => $item->id,
                        'item_type' => 'Requisition',
                        'item_code' => $item->requisition->requisition_no,
                        'requesting_unit' => $item->requisition->department->name,
                        'item' => $item->requisition->item,
                        'date_received' => $item->date_received,
                        'status' => $status,
                    ];
                });
                $query = $query->merge($requisitions);
            } elseif ($departmentName === 'Procurement Unit') {
                $forms = RequisitionRequestForm::where('reporting_officer_approval', true)->where('status', '!=', 'Completed')->get()->map(function ($item) {
                    $status = $item->status === 'Completed'
                        ? '<div style="text-align:center;"><span style="background-color: #8bc34a !important;" class="badge bg-success">Completed</span></div>'
                        : '<div style="text-align:center;"><span style="background-color: #e09e03 !important;" class="badge bg-warning">' . $item->status . '</span></div>';

                    return [
                        'id' => $item->id,
                        'item_type' => 'Requisition Form',
                        'item_code' => $item->form_code,
                        'requesting_unit' => $item->requestingUnit->name,
                        'item' => $item->items->pluck('name')->implode(', '),
                        'date_received' => $item->reporting_officer_approval_date,
                        'status' => $status,
                    ];
                });
                $query = $query->merge($forms);
            } elseif ($departmentName === 'Office of the Permanent Secretary') {
                $forms = RequisitionRequestForm::where('sent_to_ps', true)->where('reporting_officer_approval', false)->get()->map(function ($item) {
                    $status = $item->status === 'Completed'
                        ? '<div style="text-align:center;"><span style="background-color: #8bc34a !important;" class="badge bg-success">Completed</span></div>'
                        : '<div style="text-align:center;"><span style="background-color: #e09e03 !important;" class="badge bg-warning">' . $item->status . '</span></div>';

                    return [
                        'id' => $item->id,
                        'item_type' => 'Requisition Form',
                        'item_code' => $item->form_code,
                        'requesting_unit' => $item->requestingUnit->name,
                        'item' => $item->items->pluck('name')->implode(', '),
                        'date_received' => $item->reporting_officer_approval_date,
                        'status' => $status,
                    ];
                });
                $query = $query->merge($forms);
            } elseif ($departmentName === 'Office of the Deputy Permanent Secretary') {
                $forms = RequisitionRequestForm::where('sent_to_dps', true)->where('reporting_officer_approval', false)->get()->map(function ($item) {
                    $status = $item->status === 'Completed'
                        ? '<div style="text-align:center;"><span style="background-color: #8bc34a !important;" class="badge bg-success">Completed</span></div>'
                        : '<div style="text-align:center;"><span style="background-color: #e09e03 !important;" class="badge bg-warning">' . $item->status . '</span></div>';

                    return [
                        'id' => $item->id,
                        'item_type' => 'Requisition Form',
                        'item_code' => $item->form_code,
                        'requesting_unit' => $item->requestingUnit->name,
                        'item' => $item->items->pluck('name')->implode(', '),
                        'date_received' => $item->reporting_officer_approval_date,
                        'status' => $status,
                    ];
                });
                $query = $query->merge($forms);
            } elseif ($departmentName === 'Office of the Chief Medical Officer') {
                $forms = RequisitionRequestForm::where('sent_to_cmo', true)->where('reporting_officer_approval', false)->get()->map(function ($item) {
                    $status = $item->status === 'Completed'
                        ? '<div style="text-align:center;"><span style="background-color: #8bc34a !important;" class="badge bg-success">Completed</span></div>'
                        : '<div style="text-align:center;"><span style="background-color: #e09e03 !important;" class="badge bg-warning">' . $item->status . '</span></div>';

                    return [
                        'id' => $item->id,
                        'item_type' => 'Requisition Form',
                        'item_code' => $item->form_code,
                        'requesting_unit' => $item->requestingUnit->name,
                        'item' => $item->items->pluck('name')->implode(', '),
                        'date_received' => $item->reporting_officer_approval_date,
                        'status' => $status,
                    ];
                });
                $query = $query->merge($forms);
            }
        }

        return DataTables::of($query->sortByDesc('date_received'))
            ->addColumn('view', function ($row) use ($departmentName) {
                $url = '';
                if ($row['item_type'] === 'Requisition Form') {
                    $url = route('queue.form.view', $row['id']);
                } else {
                    if ($departmentName === 'Cost & Budgeting') {
                        $url = route('cost_and_budgeting.view', $row['id']);
                    } else {
                        $url = route('queue.requisition.view', $row['id']);
                    }
                }
                return '<div style="text-align:center;"><a href="' . $url . '" class="btn btn-primary btn-sm">View</a></div>';
            })
            ->rawColumns(['status', 'view'])
            ->make(true);
    }

    public function getCompletedQueue()
    {
        $user = Auth::user();
        $departmentName = $user->department->name;
        $query = collect();

        if ($user->role->name === 'Super Admin') {
            $forms = RequisitionRequestForm::where('status', 'Completed')->get()->map(function ($item) {
                $status = $item->status === 'Completed'
                    ? '<div style="text-align:center;"><span style="background-color: #8bc34a !important;" class="badge bg-success">Completed</span></div>'
                    : '<div style="text-align:center;"><span style="background-color: #e09e03 !important;" class="badge bg-warning">' . $item->status . '</span></div>';

                return [
                    'id' => $item->id,
                    'item_type' => 'Requisition Form',
                    'item_code' => $item->form_code,
                    'requesting_unit' => $item->requestingUnit->name,
                    'item' => $item->items->pluck('name')->implode(', '),
                    'date_received' => $item->created_at,
                    'status' => $status,
                ];
            });
            $query = $query->merge($forms);

            $requisitions = CBRequisition::where('is_completed', true)->with('requisition')->get()->map(function ($item) {
                $status = $item->requisition->requisition_status === 'Completed'
                    ? '<div style="text-align:center;"><span style="background-color: #8bc34a !important;" class="badge bg-success">Completed</span></div>'
                    : '<div style="text-align:center;"><span style="background-color: #e09e03 !important;" class="badge bg-warning">' . $item->requisition->requisition_status . '</span></div>';

                return [
                    'id' => $item->id,
                    'item_type' => 'Requisition',
                    'item_code' => $item->requisition->requisition_no,
                    'requesting_unit' => $item->requisition->department->name,
                    'item' => $item->requisition->item,
                    'date_received' => $item->date_received,
                    'status' => $status,
                ];
            });
            $query = $query->merge($requisitions);
        } else {
            if ($departmentName === 'Cost & Budgeting') {
                $forms = RequisitionRequestForm::where('sent_to_cab', true)->where('completed_by_cab', true)->get()->map(function ($item) {
                    $status = $item->completed_by_cab == true
                        ? '<div style="text-align:center;"><span style="background-color: #8bc34a !important;" class="badge bg-success">Completed</span></div>'
                        : '<div style="text-align:center;"><span style="background-color: #e09e03 !important;" class="badge bg-warning">' . $item->status . '</span></div>';

                    return [
                        'id' => $item->id,
                        'item_type' => 'Requisition Form',
                        'item_code' => $item->form_code,
                        'requesting_unit' => $item->requestingUnit->name,
                        'item' => $item->items->pluck('name')->implode(', '),
                        'date_received' => $item->date_sent_to_cab,
                        'status' => $status,
                    ];
                });
                $query = $query->merge($forms);

                $requisitions = CBRequisition::where('is_completed', true)->with('requisition')->get()->map(function ($item) {
                    $status = $item->is_completed == true
                        ? '<div style="text-align:center;"><span style="background-color: #8bc34a !important;" class="badge bg-success">Completed</span></div>'
                        : '<div style="text-align:center;"><span style="background-color: #e09e03 !important;" class="badge bg-warning">' . $item->requisition->requisition_status . '</span></div>';

                    return [
                        'id' => $item->id,
                        'item_type' => 'Requisition',
                        'item_code' => $item->requisition->requisition_no,
                        'requesting_unit' => $item->requisition->department->name,
                        'item' => $item->requisition->item,
                        'date_received' => $item->date_received,
                        'status' => $status,
                    ];
                });
                $query = $query->merge($requisitions);
            } elseif ($departmentName === 'Procurement Unit') {
                $forms = RequisitionRequestForm::where('reporting_officer_approval', true)->where('status', 'Completed')->get()->map(function ($item) {
                    $status = $item->status === 'Completed'
                        ? '<div style="text-align:center;"><span style="background-color: #8bc34a !important;" class="badge bg-success">Completed</span></div>'
                        : '<div style="text-align:center;"><span style="background-color: #e09e03 !important;" class="badge bg-warning">' . $item->status . '</span></div>';

                    return [
                        'id' => $item->id,
                        'item_type' => 'Requisition Form',
                        'item_code' => $item->form_code,
                        'requesting_unit' => $item->requestingUnit->name,
                        'item' => $item->items->pluck('name')->implode(', '),
                        'date_received' => $item->reporting_officer_approval_date,
                        'status' => $status,
                    ];
                });
                $query = $query->merge($forms);
            } elseif ($departmentName === 'Office of the Permanent Secretary') {
                $forms = RequisitionRequestForm::where('sent_to_ps', true)->where('reporting_officer_approval', true)->get()->map(function ($item) {
                    $status = $item->status === 'Completed'
                        ? '<div style="text-align:center;"><span style="background-color: #8bc34a !important;" class="badge bg-success">Completed</span></div>'
                        : '<div style="text-align:center;"><span style="background-color: #e09e03 !important;" class="badge bg-warning">' . $item->status . '</span></div>';

                    return [
                        'id' => $item->id,
                        'item_type' => 'Requisition Form',
                        'item_code' => $item->form_code,
                        'requesting_unit' => $item->requestingUnit->name,
                        'item' => $item->items->pluck('name')->implode(', '),
                        'date_received' => $item->reporting_officer_approval_date,
                        'status' => $status,
                    ];
                });
                $query = $query->merge($forms);
            } elseif ($departmentName === 'Office of the Deputy Permanent Secretary') {
                $forms = RequisitionRequestForm::where('sent_to_dps', true)->where('reporting_officer_approval', true)->get()->map(function ($item) {
                    $status = $item->status === 'Completed'
                        ? '<div style="text-align:center;"><span style="background-color: #8bc34a !important;" class="badge bg-success">Completed</span></div>'
                        : '<div style="text-align:center;"><span style="background-color: #e09e03 !important;" class="badge bg-warning">' . $item->status . '</span></div>';

                    return [
                        'id' => $item->id,
                        'item_type' => 'Requisition Form',
                        'item_code' => $item->form_code,
                        'requesting_unit' => $item->requestingUnit->name,
                        'item' => $item->items->pluck('name')->implode(', '),
                        'date_received' => $item->reporting_officer_approval_date,
                        'status' => $status,
                    ];
                });
                $query = $query->merge($forms);
            } elseif ($departmentName === 'Office of the Chief Medical Officer') {
                $forms = RequisitionRequestForm::where('sent_to_cmo', true)->where('reporting_officer_approval', true)->get()->map(function ($item) {
                    $status = $item->status === 'Completed'
                        ? '<div style="text-align:center;"><span style="background-color: #8bc34a !important;" class="badge bg-success">Completed</span></div>'
                        : '<div style="text-align:center;"><span style="background-color: #e09e03 !important;" class="badge bg-warning">' . $item->status . '</span></div>';

                    return [
                        'id' => $item->id,
                        'item_type' => 'Requisition Form',
                        'item_code' => $item->form_code,
                        'requesting_unit' => $item->requestingUnit->name,
                        'item' => $item->items->pluck('name')->implode(', '),
                        'date_received' => $item->reporting_officer_approval_date,
                        'status' => $status,
                    ];
                });
                $query = $query->merge($forms);
            }
        }

        return DataTables::of($query->sortByDesc('date_received'))
            ->addColumn('view', function ($row) use ($departmentName) {
                $url = '';
                if ($row['item_type'] === 'Requisition Form') {
                    $url = route('queue.form.view', $row['id']);
                } else {
                    if ($departmentName === 'Cost & Budgeting') {
                        $url = route('cost_and_budgeting.view', $row['id']);
                    } else {
                        $url = route('queue.requisition.view', $row['id']);
                    }
                }
                return '<div style="text-align:center;"><a href="' . $url . '" class="btn btn-primary btn-sm">View</a></div>';
            })
            ->rawColumns(['status', 'view'])
            ->make(true);
    }
}
