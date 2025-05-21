<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;


class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        /// return $request->expectsJson() ? null : route('login');

        // return $request->expectsJson() ? null : ResponseHelper::error_response(
        //     'Token not found.',
        //     null,
        //     401
        // );


        return $request->expectsJson() ? null : $request->json(['response' => 'Token not found']);
    }
}
