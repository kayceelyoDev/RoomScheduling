<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfUserAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // 1. If the user is logged in AND they are an admin...
        if ($user && $user->role === 'admin') {
            // Let them pass through to the Admin route they requested
            return $next($request);
        }

        // 2. If they are NOT an admin (e.g., student or teacher), boot them out
        return redirect()->route('dashboard')->with('error', 'You do not have admin access.');
    }
}
