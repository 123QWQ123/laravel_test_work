<?php

namespace App\Http\Middleware;

use Closure;

class Oauth
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

        if ($request->has('code')) {
            session(['code' => $request->get('code')]);
            if (\Api::oauth()) {
                return redirect()->route('currencies');
            }
        }

        if (!\Api::isAuth() && !$request->is('login')) {
            return redirect()->route('login');
        }

        return $next($request);


    }
}
