<?php

namespace App\Http\Controllers;

use App\Models\Rooms;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoomsController extends Controller
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
        $classrooms  = Rooms::select('id', 'roomName', 'roomType', 'status')->get();
        return view('classrooms.manageClassroom', compact('classrooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('classrooms.createClassroom');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'roomName' => 'required|string|max:255',
            'room_type' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        $rooms = Rooms::create([
            'roomName' => $validated['roomName'],
            'room_type' => $validated['room_type'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('manageClassrooms.index')->with('success', 'Classroom created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rooms $manageClassroom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rooms $manageClassroom)
    {
        //
        return view('classrooms.updateClassroom', compact('manageClassroom'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rooms $manageClassroom)
    {
        //
        $validated = $request->validate([
            'roomName' => 'required|string|max:255',
            'roomType' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        $manageClassroom->update([
            'roomName' => $validated['roomName'],
            'roomType' => $validated['roomType'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('manageClassrooms.index')->with('success', 'Classroom updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rooms $manageClassroom)
    {
        //
        $manageClassroom->delete();
        return redirect()->route('manageClassrooms.index')->with('success', 'Classroom deleted successfully.');
    }
}
