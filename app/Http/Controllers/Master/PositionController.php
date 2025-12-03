<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Department;
use App\Models\Master\Level;
use App\Models\Master\Position;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // If AJAX request, return Yajra DataTables JSON
        if ($request->ajax()) {
            try {
                $query = Position::with('department','level')->select('positions.*');
                return DataTables::of($query)->toJson();
            } catch (\Exception $e) {
                Log::error('getPositionsData error: ' . $e->getMessage());
                return response()->json(['status' => 'error', 'message' => 'Failed to get positions data', 'error' => $e->getMessage()], 500);
            }
        }

        $departments = Department::all();
        $levels = Level::all();
         // Dialog Sweet Alert
         $title = 'Delete User!';
         $text = "Are you sure you want to delete?";
         confirmDelete($title, $text);
        return view('page.master.positions.index', ['departments' => $departments,'levels' => $levels]);
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
            'department_id' => 'required|exists:departments,id',
            'level_id' => 'required|exists:levels,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }
        try {
            $position = Position::create($request->only(['name','department_id','level_id']));
            return response()->json(['status' => 'success', 'message' => 'Successfully created position', 'position' => $position]);
        } catch (\Exception $e) {
            Log::error('Position store error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to create position: ' . $e->getMessage()], 500);
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
            'department_id' => 'required|exists:departments,id',
            'level_id' => 'required|exists:levels,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }
        try {
            $position = Position::findOrFail($id);
            $position->update($request->only(['name','department_id','level_id']));
            return response()->json(['status' => 'success', 'message' => 'Successfully updated position', 'position' => $position]);
        } catch (\Exception $e) {
            Log::error('Position update error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to update position: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $position = Position::findOrFail($id);
            $position->delete();
            return response()->json(['status' => 'success', 'message' => 'Successfully deleted position']);
        } catch (\Exception $e) {
            Log::error('Position delete error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to delete position: ' . $e->getMessage()], 500);
        }
    }
}
