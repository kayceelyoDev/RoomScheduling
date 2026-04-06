<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        $sections = Section::select('id', 'sectionName', 'year_level', 'department')->get();
        return view('manageSection.sectionManagement',compact('sections'));
    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        return view('manageSection.addSection');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        $request->validate([
            'sectionName' => 'required',
            'year_level' => 'required',
            'department' => 'required',
        ]);

        Section::create([
            'sectionName' => $request->sectionName,
            'year_level' => $request->year_level,
            'department' => $request->department,
        ]);

        return redirect()->route('manageSection.index')->with('success', 'Section created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $manageSection)
    {
        //
       
        return view('manageSection.updateSection', compact('manageSection'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Section $manageSection)
    {
        //
        $request->validate([
            'sectionName' => 'required',
            'year_level' => 'required',
            'department' => 'required',
        ]);

        $manageSection->update([
            'sectionName' => $request->sectionName,
            'year_level' => $request->year_level,
            'department' => $request->department,
        ]);
        return redirect()->route('manageSection.index')->with('success', 'Section updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $manageSection)
    {
        //
        $manageSection->delete();
        return redirect()->route('manageSection.index')->with('success', 'Section deleted successfully.');
    }
}
