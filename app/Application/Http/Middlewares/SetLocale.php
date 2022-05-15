<?php

namespace App\Application\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    /**
     * @param Request $request
     * @param Closure (\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()){
            app()->setLocale($request->user()->locale);
        }

        return $next($request);
    }
}
