<?php

namespace App\Http\Controllers;

use App\Models\Rooms;
use App\Models\Section;
use App\Models\UserRequest;
use App\Services\ScheduleServices;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class UserRequestController extends Controller
{
    protected $scheduleService;

    public function __construct(ScheduleServices $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    /**
     * Teacher: form + list of own requests.
     */
    public function index(Request $request): View
    {
        $date = $request->query('date', Carbon::today()->format('Y-m-d'));

        $rooms = Rooms::orderBy('roomName')->get();
        $sections = Section::orderBy('sectionName')->get();
        $requests = UserRequest::with(['room', 'section', 'reviewer'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        $schedules = $this->scheduleService->getSchedulesForDate($date);
        $schedulesByRoom = $schedules->groupBy('room_id');

        return view('userRequest.createRequest', compact('rooms', 'sections', 'requests', 'date', 'schedulesByRoom'));
    }

    /**
     * Store a new room request (teacher).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'section_id' => 'required|exists:sections,id',
            'requested_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'reason' => 'nullable|string|max:2000',
        ]);

        UserRequest::create([
            'user_id' => auth()->id(),
            'room_id' => $validated['room_id'],
            'section_id' => $validated['section_id'],
            'requested_date' => $validated['requested_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'reason' => $validated['reason'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('userRequest.index')->with('success', 'Room request submitted. An admin will review it.');
    }

    /**
     * Teacher: cancel a pending request.
     */
    public function destroy(UserRequest $userRequest): RedirectResponse
    {
        if ($userRequest->user_id !== auth()->id() || ! $userRequest->isPending()) {
            abort(403);
        }

        $userRequest->delete();

        return redirect()->route('userRequest.index')->with('success', 'Request withdrawn.');
    }

    /**
     * Admin: all requests.
     */
    public function adminIndex(): View
    {
        $requests = UserRequest::with(['teacher', 'room', 'reviewer'])
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->latest()
            ->paginate(15);

        return view('userRequest.manageUserRequest', compact('requests'));
    }

    public function approve(Request $request, UserRequest $userRequest): RedirectResponse
    {
        if ($userRequest->status !== 'pending') {
            return back()->with('error', 'This request was already processed.');
        }

        $validated = $request->validate([
            'admin_remark' => 'nullable|string|max:2000',
        ]);

        // Automate schedule creation
        $result = $this->scheduleService->createSchedule([
            'room_id' => $userRequest->room_id,
            'section_id' => $userRequest->section_id,
            'user_id' => $userRequest->user_id,
            'date' => $userRequest->requested_date->format('Y-m-d'),
            'start_time' => $userRequest->start_time,
            'end_time' => $userRequest->end_time,
            'is_recurring' => false,
        ]);

        if (!$result['success']) {
            return back()->with('error', 'Conflict: ' . $result['message']);
        }

        $userRequest->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'admin_remark' => $validated['admin_remark'] ?? null,
        ]);

        return back()->with('success', 'Request approved and schedule created.');
    }

    public function reject(Request $request, UserRequest $userRequest): RedirectResponse
    {
        if ($userRequest->status !== 'pending') {
            return back()->with('error', 'This request was already processed.');
        }

        $validated = $request->validate([
            'admin_remark' => 'nullable|string|max:2000',
        ]);

        $userRequest->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'admin_remark' => $validated['admin_remark'] ?? null,
        ]);

        return back()->with('success', 'Request rejected.');
    }
}
