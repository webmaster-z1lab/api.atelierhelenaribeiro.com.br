<?php

namespace App\Http\Middleware;

use Closure;

class PreviousURL
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
        if ($request->isMethod('GET')) {
            if (\Session::get('url_current') !== $request->url()) {
                \Session::put('url_previous', \Session::get('url_current'));
            }

            \Session::put('url_current', \URL::current());
        }

        return $next($request);
    }
}
