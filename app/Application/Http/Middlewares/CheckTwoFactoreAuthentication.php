<?php

namespace App\Application\Http\Middlewares;

use App\Infrastructure\Supports\TwoFactorAuthenticator;
use App\Interfaces\Http\Controllers\ResponseTrait;
use Closure;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckTwoFactoreAuthentication
{
    use ResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return Response|RedirectResponse
     * @throws Exception
     */
    public function handle(Request $request, Closure $next)
    {
        $twoFactorAuthentication = new TwoFactorAuthenticator($request);

        if ($twoFactorAuthentication->isAuthenticated()){
            return $next($request);
        }

        $message = __('Verifikasi Code 2FA Salah, coba lagi');

        return $next($request);
    }
}
