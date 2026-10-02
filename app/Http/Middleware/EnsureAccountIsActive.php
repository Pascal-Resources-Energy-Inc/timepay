<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureAccountIsActive {
    public function handle($request, Closure $next) {
        $user = Auth::user();

        $inactiveUser = $user && $user->status === 'Inactive';

        $inactiveEmployee = $user->employee && in_array(
                $user->employee->status,
                ['Inactive', 'Terminated'],
                true
            );

        if ($inactiveUser || $inactiveEmployee) {
            Auth::logout();
            $request->session()->invalidate();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Your account is no longer active.',
                ], 403);
            }


            return redirect()->route('login')->withErrors([
                'email' => 'Your account is no longer active.'
            ]);
        }

        return $next($request);
    }
}
