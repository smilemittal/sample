<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ActiveSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            if (! auth()->user()->company->subscription('default')->cancelled()) {
                return $next($request);
            }

            return response()->json([
                'message' => 'No Active Subscription Found',
            ], 400);
        }

        return redirect('/');
    }
}
