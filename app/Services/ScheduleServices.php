<?php

namespace App\Services;

use App\Models\Schedule;
use Illuminate\Support\Carbon;

class ScheduleServices
{
    /**
     * Create a new schedule if no conflict exists.
     */
    public function createSchedule(array $data)
    {
        $roomId = $data['room_id'];
        $date = Carbon::parse($data['date']);
        $startTime = Carbon::parse($data['start_time'])->format('H:i:s');
        $endTime = Carbon::parse($data['end_time'])->format('H:i:s');
        
        $isRecurring = !empty($data['is_recurring']) && in_array($data['is_recurring'], ['on', '1', 1, true, 'true'], true);
        
        $repeatType = $isRecurring ? ($data['repeat_type'] ?? 'custom') : null;
        $repeatUntil = $isRecurring && !empty($data['repeat_until']) ? Carbon::parse($data['repeat_until'])->format('Y-m-d') : null;
        
        $repeatDays = $isRecurring && isset($data['repeat_days']) ? $data['repeat_days'] : [];

        if ($isRecurring && $repeatType !== 'custom' && empty($repeatDays)) {
            if ($repeatType === 'daily') $repeatDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
            if ($repeatType === 'mwf') $repeatDays = ['Monday', 'Wednesday', 'Friday'];
            if ($repeatType === 'tth') $repeatDays = ['Tuesday', 'Thursday'];
        }

        if ($this->hasConflict($roomId, $date->format('Y-m-d'), $startTime, $endTime, $isRecurring, $repeatDays, $repeatUntil)) {
            return ['success' => false, 'message' => 'The selected room is already scheduled for another section during these times/days.'];
        }

        Schedule::create([
            'room_id' => $roomId,
            'section_id' => $data['section_id'],
            'user_id' => $data['user_id'],
            'date' => $date->format('Y-m-d'),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'is_recurring' => $isRecurring,
            'repeat_type' => $repeatType,
            'repeat_days' => $repeatDays,
            'repeat_until' => $repeatUntil,
        ]);

        return ['success' => true, 'message' => 'Schedule created successfully.'];
    }

    /**
     * UPDATE an existing schedule if no conflict exists.
     */
    public function updateSchedule(Schedule $schedule, array $data)
    {
        $roomId = $data['room_id'];
        $date = Carbon::parse($data['date']);
        $startTime = Carbon::parse($data['start_time'])->format('H:i:s');
        $endTime = Carbon::parse($data['end_time'])->format('H:i:s');
        
        $isRecurring = !empty($data['is_recurring']) && in_array($data['is_recurring'], ['on', '1', 1, true, 'true'], true);
        
        $repeatType = $isRecurring ? ($data['repeat_type'] ?? 'custom') : null;
        $repeatUntil = $isRecurring && !empty($data['repeat_until']) ? Carbon::parse($data['repeat_until'])->format('Y-m-d') : null;
        
        $repeatDays = $isRecurring && isset($data['repeat_days']) ? $data['repeat_days'] : [];

        if ($isRecurring && $repeatType !== 'custom' && empty($repeatDays)) {
            if ($repeatType === 'daily') $repeatDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
            if ($repeatType === 'mwf') $repeatDays = ['Monday', 'Wednesday', 'Friday'];
            if ($repeatType === 'tth') $repeatDays = ['Tuesday', 'Thursday'];
        }

        // Pass the $schedule->id to ignore it! We don't want it to conflict with its own old timeframe.
        if ($this->hasConflict($roomId, $date->format('Y-m-d'), $startTime, $endTime, $isRecurring, $repeatDays, $repeatUntil, $schedule->id)) {
            return ['success' => false, 'message' => 'The selected room is already scheduled for another section during these times/days.'];
        }

        $schedule->update([
            'room_id' => $roomId,
            'section_id' => $data['section_id'],
            'user_id' => $data['user_id'],
            'date' => $date->format('Y-m-d'),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'is_recurring' => $isRecurring,
            'repeat_type' => $repeatType,
            'repeat_days' => $repeatDays,
            'repeat_until' => $repeatUntil,
        ]);

        return ['success' => true, 'message' => 'Schedule updated successfully.'];
    }

    /**
     * Get all schedules that occur on a specific date.
     */
    public function getSchedulesForDate(string $date)
    {
        $targetDate = Carbon::parse($date);
        $dayOfWeek = $targetDate->format('l');

        return Schedule::with(['section', 'user', 'room'])
            ->where(function ($query) use ($date, $dayOfWeek) {
                $query->where('is_recurring', false)
                      ->where('date', $date);
            })
            ->orWhere(function ($query) use ($date, $dayOfWeek) {
                $query->where('is_recurring', true)
                      ->where('date', '<=', $date)
                      ->where(function ($q) use ($date) {
                          $q->whereNull('repeat_until')
                            ->orWhere('repeat_until', '>=', $date);
                      })
                      ->whereJsonContains('repeat_days', $dayOfWeek);
            })
            ->get();
    }

    /**
     * Check if there's a conflict in the schedule. 
     * ADDED: $ignoreScheduleId
     */
    public function hasConflict($roomId, $startDate, $startTime, $endTime, $isRecurring, $repeatDays, $repeatUntil, $ignoreScheduleId = null)
    {
        $potentialConflicts = Schedule::where('room_id', $roomId)
            ->when($ignoreScheduleId, function($query) use ($ignoreScheduleId) {
                // If we are updating, ignore the ID of the schedule we are currently editing
                return $query->where('id', '!=', $ignoreScheduleId);
            })
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
            })->get();

        if ($potentialConflicts->isEmpty()) {
            return false;
        }

        $newStartDate = Carbon::parse($startDate);
        $newEndDate = $isRecurring && $repeatUntil ? Carbon::parse($repeatUntil) : $newStartDate;
        
        $newScheduleDays = $isRecurring ? $repeatDays : [$newStartDate->format('l')];

        foreach ($potentialConflicts as $existing) {
            $existingStartDate = Carbon::parse($existing->date);
            $existingEndDate = $existing->is_recurring && $existing->repeat_until 
                                ? Carbon::parse($existing->repeat_until) 
                                : $existingStartDate;
            
            if ($existingStartDate <= $newEndDate && $existingEndDate >= $newStartDate) {
                $existingDays = $existing->is_recurring && is_array($existing->repeat_days) 
                                ? $existing->repeat_days 
                                : [$existingStartDate->format('l')];
                
                $intersectingDays = array_intersect($newScheduleDays, $existingDays);
                
                if (!empty($intersectingDays)) {
                    return true;
                }
            }
        }
        
        return false;
    }
}