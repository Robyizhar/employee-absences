<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Company;

class DepartmentController extends Controller
{
    public function index() {
        $companies = Company::all();
        return view('department.index', compact('companies'));
    }

    public function list(Request $request) {
        $perPage = 10;
        $lastId = $request->get('last_id', null);

        $query = Department::with('company')->orderBy('id', 'asc');

        if ($lastId) {
            $query->where('id', '>', $lastId);
        }

        $data = $query->take($perPage)->get();

        return response()->json([
            'data' => $data,
            'hasMore' => $data->count() === $perPage
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'name'       => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'code'       => 'required|string|max:50|unique:departments,code',
            'start_time' => 'required',
            'end_time'   => 'required',
        ]);

        $department = Department::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Department created successfully.',
            'data' => $department
        ]);
    }

    public function update(Request $request, Department $department) {
        $request->validate([
            'name'       => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'code'       => 'required|string|max:50|unique:departments,code,' . $department->id,
            'start_time' => 'required',
            'end_time'   => 'required',
        ]);

        $department->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Department updated successfully.',
            'data' => $department
        ]);
    }

    public function destroy(Department $department) {
        $department->delete();

        return response()->json([
            'success' => true,
            'message' => 'Department deleted successfully.'
        ]);
    }
}
