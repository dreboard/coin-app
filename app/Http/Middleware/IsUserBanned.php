<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsUserBanned
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
        if (auth()->check() && auth()->user()->account_status != 'Active') {
            auth()->logout();
            return redirect()->route('login')->with('message', 'Your Account is suspended, please contact Admin');
        }
        return $next($request);
    }
}
