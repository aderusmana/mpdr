<?php

namespace App\Http\Controllers\Master;

use App\Models\Master\Level;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Level::query();
            return DataTables::of($query)->toJson();
        }

        $levels = Level::all();

        // Dialog Sweet Alert (client now handles confirmations)
        $title = 'Delete User!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        return view('page.master.levels.index', compact('levels'));
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
        $v = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($v->fails()) {
            return response()->json(["status" => "error", "message" => "Validation failed", "errors" => $v->errors()], 422);
        }

        try {
            Level::create($request->only(['name']));
            return response()->json(["status" => "success", "message" => "Successfully created level"]);
        } catch (\Exception $e) {
            Log::error('Level store error: ' . $e->getMessage());
            return response()->json(["status" => "error", "message" => "Failed to create level"], 500);
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
        $v = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($v->fails()) {
            return response()->json(["status" => "error", "message" => "Validation failed", "errors" => $v->errors()], 422);
        }

        try {
            $level = Level::findOrFail($id);
            $level->update($request->only(['name']));
            return response()->json(["status" => "success", "message" => "Successfully updated level"]);
        } catch (\Exception $e) {
            Log::error('Level update error: ' . $e->getMessage());
            return response()->json(["status" => "error", "message" => "Failed to update level"], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $level = Level::findOrFail($id);
            $level->delete();
            return response()->json(["status" => "success", "message" => "Successfully deleted level"]);
        } catch (\Exception $e) {
            Log::error('Level destroy error: ' . $e->getMessage());
            return response()->json(["status" => "error", "message" => "Failed to delete level"], 500);
        }
    }
}
