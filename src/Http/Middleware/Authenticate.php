<?php

namespace Sausin\Signere\Http\Middleware;

use Sausin\Signere\Signere;

class Authenticate
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response|null
     */
    public function handle($request, $next)
    {
        return Signere::check($request) ? $next($request) : abort(404);
    }
}
