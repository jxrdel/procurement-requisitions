<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use App\Rules\UniqueHeadOfDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class DepartmentController extends Controller
{
    public function getDepartments(Request $request)
    {
        if ($request->ajax()) {
            $data = Department::with('headOfDepartment')->select('departments.*');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('head_of_department', function ($row) {
                    return $row->headOfDepartment->name ?? 'N/A';
                })
                ->addColumn('action', function($row){
                    $headOfDepartmentId = $row->head_of_department_id ?? 'null';
                    $btn = '<div style="text-align:center"><a href="javascript:void(0)" onclick="showEdit('.$row->id.', \''.$row->name.'\', '.$headOfDepartmentId.')" class="btn btn-primary btn-sm">Edit</a></div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $users = User::all();
        return view('departments.index', compact('users'));
    }

    public function update(Request $request, Department $department)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'head_of_department_id' => ['nullable', 'exists:users,id', new UniqueHeadOfDepartment($department->id)],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $department->update($request->all());

        return response()->json(['success' => 'Department updated successfully.']);
    }
}
