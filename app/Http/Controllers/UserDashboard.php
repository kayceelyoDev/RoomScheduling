<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Carbon\Carbon;

class UserDashboard extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Redirect admins away from the student/teacher dashboard
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $schedules = collect();
        $sectionName = 'Unassigned';
        $departmentName = null;

        // Fetch Schedule for STUDENT
        if ($user->role === 'student' && $user->profile && $user->profile->section_id) {
            $schedules = Schedule::with(['user', 'room', 'section'])
                ->where('section_id', $user->profile->section_id)
                ->orderBy('start_time')
                ->get();

            $sectionName = $user->profile->section->sectionName ?? 'Unknown Section';
            $departmentName = $user->profile->section->department ?? null;
        }
        // Fetch Schedule for TEACHER
        elseif ($user->role === 'teacher') {
            // Added 'section' to the 'with' array so teachers can see what section they are teaching!
            $schedules = Schedule::with(['room', 'section']) 
                ->where('user_id', $user->id)
                ->orderBy('start_time')
                ->get();

            $sectionName = 'My Teaching Schedule';
        }

        // --- NEW RECURRING SCHEDULE LOGIC ---
        
        // 1. Create empty buckets for every day of the week
        $weeklySchedule = collect([
            'Monday' => collect(),
            'Tuesday' => collect(),
            'Wednesday' => collect(),
            'Thursday' => collect(),
            'Friday' => collect(),
            'Saturday' => collect()
        ]);

        // 2. Sort the database records into the buckets
        foreach ($schedules as $schedule) {
            // If it is a recurring class (e.g., TTH)
            if ($schedule->is_recurring && is_array($schedule->repeat_days)) {
                
                foreach ($schedule->repeat_days as $day) {
                    // Ensure the day string matches exactly (e.g., "Tuesday")
                    $formattedDay = ucfirst(strtolower($day)); 
                    
                    if ($weeklySchedule->has($formattedDay)) {
                        $weeklySchedule[$formattedDay]->push($schedule);
                    }
                }
                
            } else {
                // If it is a single-day class, use the 'date' column
                $dayName = Carbon::parse($schedule->date)->format('l');
                
                if ($weeklySchedule->has($dayName)) {
                    $weeklySchedule[$dayName]->push($schedule);
                }
            }
        }

        // 3. Clean up: Sort each day by time, and remove empty days so the "Free Day" UI works
        $weeklySchedule = $weeklySchedule->map(function ($schedulesOnDay) {
            return $schedulesOnDay->sortBy('start_time')->values();
        })->filter(function ($schedulesOnDay) {
            return $schedulesOnDay->isNotEmpty();
        });

        $userRole = $user->role;

        return view('dashboard', compact('weeklySchedule', 'sectionName', 'departmentName', 'userRole'));
    }
}