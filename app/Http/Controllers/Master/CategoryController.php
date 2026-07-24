<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Category;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\ConditionQueryTrait;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use ConditionQueryTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Category::query();
            if ($request->has('searchBuilder')) {
                $this->applySearchBuilder($query, $request->input('searchBuilder'));
            }
            return DataTables::of($query)->toJson();
        }

        $categories = Category::all();
        
        $title = 'Delete Category!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        
        return view('page.master.categories.index', compact('categories'));
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
            $category = Category::create($request->all());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => 'Successfully created category', 'category' => $category]);
            }
            Alert::toast('Successfully create category', 'success');
            return redirect()->route('categories.index');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Failed to create category: ' . $e->getMessage()], 500);
            }
            throw $e;
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
            $category = Category::findOrFail($id);
            $category->update($request->all());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => 'Successfully updated category', 'category' => $category]);
            }
            Alert::toast('Successfully update category', 'success');
            return redirect()->route('categories.index');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Failed to update category: ' . $e->getMessage()], 500);
            }
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => 'Category deleted successfully']);
            }
            Alert::toast('Successfully delete category', 'success');
            return redirect()->route('categories.index');
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Failed to delete category: ' . $e->getMessage()], 500);
            }
            throw $e;
        }
    }
}
