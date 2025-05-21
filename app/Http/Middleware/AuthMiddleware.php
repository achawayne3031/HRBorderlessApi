<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Auth;


class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $bearerToken = $request->bearerToken();

        if ($bearerToken) {
            if (!Auth::check('company')) {
                return ResponseHelper::error_response(
                    'Unauthorised access.',
                    null,
                    401
                );
            }

            return $next($request);
        } else {
            return ResponseHelper::error_response(
                'Token Not Found',
                null,
                401
            );
        }
    }
}
