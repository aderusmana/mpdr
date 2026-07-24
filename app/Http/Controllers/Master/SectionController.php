<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\ConditionQueryTrait;

class SectionController extends Controller
{
    use ConditionQueryTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = Section::query();
                if ($request->has('searchBuilder')) {
                    $this->applySearchBuilder($query, $request->input('searchBuilder'));
                }
                return DataTables::of($query)->toJson();
            } catch (\Exception $e) {
                Log::error('Section index (ajax) error: ' . $e->getMessage());
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to get sections data',
                ], 500);
            }
        }

        $title = 'Delete Section!';
        $text = 'Are you sure you want to delete?';
        confirmDelete($title, $text);

        return view('page.master.sections.index');
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
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $v->errors(),
            ], 422);
        }

        try {
            $section = Section::create($request->only(['name']));
            return response()->json([
                'status' => 'success',
                'message' => 'Section created successfully',
                'section' => $section,
            ]);
        } catch (\Exception $e) {
            Log::error('Section store error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create section',
            ], 500);
        }
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
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $v->errors(),
            ], 422);
        }

        try {
            $section = Section::findOrFail($id);
            $section->update($request->only(['name']));
            return response()->json([
                'status' => 'success',
                'message' => 'Section updated successfully',
                'section' => $section,
            ]);
        } catch (\Exception $e) {
            Log::error('Section update error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update section',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $section = Section::findOrFail($id);
            $section->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Section deleted successfully',
            ]);
        } catch (\Exception $e) {
            Log::error('Section destroy error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete section',
            ], 500);
        }
    }
}
