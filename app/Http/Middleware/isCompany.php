<?php

namespace App\Http\Middleware;

use Closure;

class isCompany
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
        if (auth()->user()->user_type == 2 || auth()->user()->user_type == 3) {
            return $next($request);
        }

        return redirect('home')->with('alert-danger', "You don't have permission to this site");
    }
}
