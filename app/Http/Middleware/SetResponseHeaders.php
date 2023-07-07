<?php

namespace App\Http\Middleware;

use App\Traits\CompanyAuthorization;
use Auth;
use Closure;
use Illuminate\Http\Request;

class SetResponseHeaders
{
    use CompanyAuthorization;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        if (auth()->check()) {
            $response->headers->set('User-Auth', json_encode($this->getComapnyAuth()));
            $response->headers->set('User', json_encode(auth()->user()->load('role')));
        }

        return $response;
    }
}
