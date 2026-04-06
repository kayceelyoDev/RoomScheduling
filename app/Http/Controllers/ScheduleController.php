<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Rooms;
use App\Models\Section;
use App\Models\User;
use App\Services\ScheduleServices;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ScheduleController extends Controller
{
    protected $scheduleService;

    public function __construct(ScheduleServices $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $date = $request->query('date', Carbon::today()->format('Y-m-d'));

        $rooms = Rooms::all();
        $schedules = $this->scheduleService->getSchedulesForDate($date);
        $schedulesByRoom = $schedules->groupBy('room_id');

        return view('schedule.manageSchedule', compact('rooms', 'schedulesByRoom', 'date'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $date = $request->query('date', Carbon::today()->format('Y-m-d'));

        $teachers = User::where('role', 'teacher')->get();
        $rooms = Rooms::all();
        $sections = Section::all();

        $schedules = $this->scheduleService->getSchedulesForDate($date);
        $schedulesByRoom = $schedules->groupBy('room_id');

        return view('schedule.createSchedule', compact('teachers', 'rooms', 'sections', 'date', 'schedulesByRoom'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'section_id' => 'required|exists:sections,id',
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'is_recurring' => 'nullable',
        ]);

        $result = $this->scheduleService->createSchedule($request->all());

        if (!$result['success']) {
            return back()->withInput()->withErrors(['conflict' => $result['message']]);
        }

        return redirect()->route('manageSchedule.index')->with('success', $result['message']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $manageSchedule)
    {
        $teachers = User::where('role', 'teacher')->get();
        $rooms = Rooms::all();
        $sections = Section::all();

        // Fetch schedules for the preview timeline so they can see conflicts while editing
        $schedules = $this->scheduleService->getSchedulesForDate($manageSchedule->date);
        $schedulesByRoom = $schedules->groupBy('room_id');

        return view('schedule.updateSchedule', compact('manageSchedule', 'teachers', 'rooms', 'sections', 'schedulesByRoom'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $manageSchedule)
    {
        // 1. Basic validation
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'section_id' => 'required|exists:sections,id',
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'is_recurring' => 'nullable',
            'repeat_type' => 'nullable|string',
            'repeat_days' => 'nullable|array',
            'repeat_until' => 'nullable|date',
        ]);

        // 2. Pass to the strict Service logic
        $result = $this->scheduleService->updateSchedule($manageSchedule, $request->all());

        // 3. If the service found a conflict, send them back with the error message
        if (!$result['success']) {
            return back()->withInput()->withErrors(['conflict' => $result['message']]);
        }

        // 4. If success, redirect back to the schedule viewer for that specific date
        return redirect()->route('manageSchedule.index', ['date' => $validated['date']])
            ->with('success', $result['message']);
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $manageSchedule)
    {
        // Save the date so we can redirect back to the same calendar day
        $date = $manageSchedule->date;

        // Delete the schedule
        $manageSchedule->delete();

        // Redirect back to the daily schedule view with a success message
        return redirect()->route('manageSchedule.index', ['date' => $date])
            ->with('success', 'Schedule deleted successfully!');
    }
}
