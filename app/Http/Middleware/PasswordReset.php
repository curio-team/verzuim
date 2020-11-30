<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PasswordReset
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
        if(\Auth::user()->password_once && \Route::currentRouteName() != 'settings.show' && \Route::currentRouteName() != 'settings.save')
        {
            return redirect()->route('settings.show');
        }
        return $next($request);
    }
}
