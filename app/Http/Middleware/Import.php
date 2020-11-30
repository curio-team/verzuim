<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Import
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
        if(\Auth::user()->importer != true) return redirect()->route('home')->with('status', ['warning' => 'Enkel voor importeerders!']);
        return $next($request);
    }
}
