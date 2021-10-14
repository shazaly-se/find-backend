<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use App\Models\User;
use Auth;
use Cache;
use Carbon\Carbon;

use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTMiddleware extends BaseMiddleware
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
        try {
            $user = JWTAuth::parseToken()->authenticate();
              $expiresAt = now()->addMinutes(2); /* already given time here we already set 2 min. */
            Cache::put('user-is-online-' . Auth::user()->id, true, $expiresAt);
  
            /* user last seen */
            User::where('id', Auth::user()->id)->update(['last_seen' => now()]);
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['status' => 'Token is Invalid']);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['status' => 'Token has Expired']);
            }else{
                return response()->json(['status' => 'Authorization Token not found']);
            }
        }
        return $next($request);
    }
}