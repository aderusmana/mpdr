<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Section;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::all();
        return view('page.master.section.index', compact('sections'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Section::create($request->all());
        Alert::toast('Section created successfully', 'success');
        return redirect()->route('sections.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $section = Section::findOrFail($id);
        $section->update($request->all());
        Alert::toast('Section updated successfully', 'success');
        return redirect()->route('sections.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $section = Section::findOrFail($id);
        $section->delete();
        Alert::toast('Section deleted successfully', 'success');
        return redirect()->route('sections.index');
    }
}
