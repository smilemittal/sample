<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ResumeSubscription
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
            if (auth()->user()->company->subscription('default')->onGracePeriod()) {
                return $next($request);
            }

            return response()->json([
                'message' => 'No Subscription Found to Resume',
            ], 400);
        }

        return redirect('/');
    }
}
