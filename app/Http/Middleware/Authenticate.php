<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Response;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        /*return ResponseBuilder::asError(Response::HTTP_FORBIDDEN)
            ->withHttpCode(SOMETHING_WENT_WRONG)*/
        /*if (! $request->expectsJson()) {
            return route('login');
        }*/
    }
}
