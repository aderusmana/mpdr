<?php

namespace App\Http\Controllers\Rs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RequistionSlipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return \view('page.rs.list-rs');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return \view('page.rs.create-rs');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        return \view('page.rs.edit-rs');

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function report()
    {
        return view('page.rs.report-rs');
    }

    public function approval()
    {
        return view('page.rs.approval-rs');
    }

    public function log()
    {
        return view('page.rs.log-rs');
    }
}
