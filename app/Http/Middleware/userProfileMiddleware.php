<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class userProfileMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            $profile = $user->profile;

            // Rule for Students: Must have a profile AND a section
            if ($user->role === 'student') {
                if (!$profile || is_null($profile->section_id)) {
                    if (!$request->routeIs('user-profile.*')) {
                        return redirect()->route('user-profile.index');
                    }
                }
            }

            // Rule for Teachers: Must have a profile (ID number), but NO section needed
            if ($user->role === 'teacher') {
                if (!$profile || is_null($profile->id_number)) {
                    if (!$request->routeIs('user-profile.*')) {
                        return redirect()->route('user-profile.index');
                    }
                }
            }
        }

        return $next($request);
    }
}