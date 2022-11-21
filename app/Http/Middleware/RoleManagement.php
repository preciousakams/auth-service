<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class RoleManagement
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (!$request->user()->hasRole($role)) {
            return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                ->withHttpCode(Response::HTTP_BAD_REQUEST)
                ->withMessage(trans('validation.role_failed'))
                ->build();
        }
        return $next($request);
    }
}
