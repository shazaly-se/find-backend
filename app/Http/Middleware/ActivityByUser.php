<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Cache;
use Carbon\Carbon;
class ActivityByUser
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
        if(auth()->check()) {
            $expiresAt = Carbon::now()->addMinutes(1);
            Cache::put('user-is-online-' . auth()->user()->id, true, $expiresAt);
            User::where('id', Auth::user()->id)->update(['last_seen' => now()]);
        }
        return $next($request);
    }
}
