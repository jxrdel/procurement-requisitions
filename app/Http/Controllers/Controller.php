<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class Controller
{
    public function index()
    {
        if (Auth::user()->department === 'Cost & Budgeting') {
            return redirect()->route('cost_and_budgeting.index');
        } elseif (Auth::user()->department === 'Vote Control') {
            return redirect()->route('vote_control.index');
        } elseif (Auth::user()->department === 'Check Staff') {
            return redirect()->route('check_room.index');
        } elseif (Auth::user()->department === 'Cheque Processing') {
            return redirect()->route('cheque_processing.index');
        }
        //Count of all requisitions
        $allRequisitionsCount = Requisition::count();

        //Count of completed requisitions
        $completedRequisitionsCount = Requisition::where('is_completed', true)->count();

        //Count of requisitions in progress
        $inprogressRequisitionsCount = Requisition::where('is_completed', false)->count();

        //If the user is a Viewer and not in Procurement or PS Office, only show their department's requisitions i.e. For Jesse's account
        if (Auth::user()->role->name === 'Viewer' && Auth::user()->department !== 'Procurement' && Auth::user()->department !== 'PS Office') {
            $allRequisitionsCount = Requisition::join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
                ->where('departments.name', Auth::user()->department)
                ->count();

            $completedRequisitionsCount = Requisition::where('is_completed', true)
                ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
                ->where('departments.name', Auth::user()->department)
                ->count();

            $inprogressRequisitionsCount = Requisition::where('is_completed', false)
                ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
                ->where('departments.name', Auth::user()->department)
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
            $completed_at = Carbon::parse($requisition->vote_control_requisition->date_completed);
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
        $users = User::all();

        return DataTables::of($users)->make(true);
    }
}
