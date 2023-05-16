<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IframeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (isset($request->token)) {
            $request->headers->set('Authorization', sprintf('%s %s', 'Bearer', $request->token));
        }
        return $next($request);
    }
}
