<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Department;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;


class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // If AJAX request, return Yajra DataTables JSON
        if ($request->ajax()) {
            try {
                $query = Department::with('departmentHead')->select('departments.*');
                return DataTables::of($query)->toJson();
            } catch (\Exception $e) {
                Log::error('getDepartmentsData error (from index): ' . $e->getMessage());
                return response()->json(['status' => 'error', 'message' => 'Failed to get departments data', 'error' => $e->getMessage()], 500);
            }
        }

        // Non-AJAX: render the view
        // Dialog Sweet Alert
        $title = 'Delete Department!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        return \view('page.master.departments.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        try {
            $department = Department::create([
                'name' => $request->name,
            ]);
            return response()->json(['status' => 'success', 'message' => 'Department created successfully', 'department' => $department]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to create department: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        try {
            $department = Department::findOrFail($id);
            $department->update(['name' => $request->name]);
            return response()->json(['status' => 'success', 'message' => 'Department updated successfully', 'department' => $department]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to update department: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $department = Department::findOrFail($id);
            $department->delete();
            return response()->json(['status' => 'success', 'message' => 'Department deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to delete department: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Return departments data as JSON for AJAX refresh
     */
    public function getDepartmentsData()
    {
        try {
            $query = Department::with('departmentHead')->select('departments.*');
            $json = DataTables::of($query)->toJson();
            // Log first part of JSON to help debug invalid/empty responses
            Log::info('getDepartmentsData output (truncated): ' . substr($json, 0, 2000));
            return response($json, 200, ['Content-Type' => 'application/json']);
        } catch (\Exception $e) {
            Log::error('getDepartmentsData error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to get departments data', 'error' => $e->getMessage()], 500);
        }
    }
}
