<?php

namespace App\Http\Middleware;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class IsOnline
{

    private const ONLINE_FOR = 3;

    private const LAST_SEEN = "Y-m-d H:i:s";

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (Auth::check()) {
            $expiresAt = Carbon::now()->addMinutes(self::ONLINE_FOR);
            Cache::put('user-is-online-' . Auth::user()->id, true, $expiresAt);
            // last seen
            User::where('id', Auth::user()->id)->update(['last_seen' => (new \DateTime())->format(self::LAST_SEEN)]);
        }
        return $next($request);
    }
}
