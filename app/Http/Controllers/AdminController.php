<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rooms;
use App\Models\Section;
use App\Models\User;
use App\Services\ScheduleServices;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    protected $scheduleService;

    public function __construct(ScheduleServices $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    public function index(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $date = $request->query('date', Carbon::today()->format('Y-m-d'));

        // Basic KPI Stats
        $totalRooms = Rooms::count();
        $totalSections = Section::count();
        $totalTeachers = User::where('role', 'teacher')->count();
        
        $rooms = Rooms::all();
        $schedules = $this->scheduleService->getSchedulesForDate($date);
        $schedulesByRoom = $schedules->groupBy('room_id');
        
        $totalActiveSchedules = $schedules->count();

        return view('adminDashboard', compact(
            'date',
            'rooms',
            'schedulesByRoom',
            'totalRooms',
            'totalSections',
            'totalTeachers',
            'totalActiveSchedules'
        ));
    }
}
