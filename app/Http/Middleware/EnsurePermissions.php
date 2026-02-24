<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class EnsurePermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user is active (status == 1) and not deleted (delete_status == 0)
        if ($user->status != 1 || $user->delete_status != 0) {
            Auth::logout();
            Session::flush();
            return redirect()->route('login')->with('error_message', 'Your account is inactive or deleted.');
        }

        // Check if user has permission_status enabled
        if ($user->permission_status != 1) {
             return redirect()->route('login')->with('error_message', 'You do not have permission to access the admin panel.');
        }

        // Future: Add granular permission checks based on UserGroup here if needed
        // for now, we ensure they are a valid admin user.

        return $next($request);
    }
}
