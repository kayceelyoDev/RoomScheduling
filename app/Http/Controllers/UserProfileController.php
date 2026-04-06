<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\UserProfile;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $user = auth()->user();

        if ($user->role == 'admin') {
            return view('dashboard');
        }


        $sections = Section::select('id', 'sectionName', 'year_level', 'department')->get();
        return view('createProfile', compact(['sections', 'user']));
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
        // 1. Base validation rule for everyone
        $rules = [
            'id_number' => 'required|string',
        ];

        // 2. Conditional validation: Only require the section if they are a student
        if (auth()->user()->role === 'student') {
            $rules['section_id'] = 'required|exists:sections,id';
        } else {
            $rules['section_id'] = 'nullable|exists:sections,id';
        }

        // 3. Run the validation
        $validated = $request->validate($rules);

        // 4. Create the profile cleanly
        UserProfile::create([
            'user_id' => auth()->id(), // Safely grab the logged-in user's ID
            'id_number' => $validated['id_number'],

            // Ensure section_id is null for non-students, even if they somehow sent one
            'section_id' => auth()->user()->role === 'student' ? $validated['section_id'] : null,
        ]);

        // 5. Redirect on success
        return redirect()->route('dashboard')->with('success', 'Profile created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserProfile $userProfile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserProfile $userProfile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserProfile $userProfile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserProfile $userProfile)
    {
        //
    }
}
