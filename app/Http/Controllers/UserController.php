<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    //

    public function index()
    {

        $users = User::whereIn('role', ['teacher', 'admin'])
            ->select('id', 'name', 'email', 'role', 'created_at')
            ->get();
        return view('user.userManagement', compact('users'));
    }

    public function create()
    {
        return view('user.createUser');
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,teacher',
        ]);

        // Create a new user with the validated data
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        // Redirect back to the user management page with a success message
        return redirect()->route('manageUsers.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('user.updateUser', compact('user'));
    }

    public function update(Request $request, User $user) // CHANGED to $user
    {
        if ($request->update_type === 'profile') {
            // Validate and update Profile Info
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users')->ignore($user->id), // Now safely ignores the actual user's ID
                ],
                'role' => ['required', 'string', 'in:admin,teacher,user'], 
            ]);

            $user->update($validated);

            return back()->with('status', 'profile-updated')->with('success', 'Profile updated successfully.');
        }

        if ($request->update_type === 'password') {
            // Validate and update Password
            $validated = $request->validate([
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            $user->update([
                'password' => bcrypt($validated['password']),
            ]);

            return back()->with('status', 'password-updated')->with('success', 'Password updated successfully.');
        }

        return back();
    }
    
    public function destroy(User $user) // CHANGED to $user
    {
        $user->delete();
        return redirect()->route('manageUsers.index')->with('success', 'User deleted successfully.');
    }
}
