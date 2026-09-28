<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckSetupComplete
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {

            $user = Auth::user();

            // ✅ If NOT setup complete
            if ($user->is_setup_complete != 1) {

                // ✅ Allow ONLY setup routes
                if (!$request->is('setup') && !$request->is('setup/*')) {

                    return redirect()->route('setup.page'); // your setup page route
                }
            }
        }

        return $next($request);
    }
}
