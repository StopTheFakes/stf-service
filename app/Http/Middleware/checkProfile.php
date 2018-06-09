<?php

namespace App\Http\Middleware;

use App\Models\Profile;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class checkProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && $request->getPathInfo() != '/settings') {
            $profile = Profile::where('user_id', Auth::user()->id)->first();
            if(empty($profile)) {
                return redirect('settings');
            }
        }

        return $next($request);

    }
}