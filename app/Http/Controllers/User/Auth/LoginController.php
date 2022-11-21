<?php

namespace App\Http\Controllers\User\Auth;

use App\Constants\GoogleSheets\UserReport;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\LoginRequest;
use App\Models\User;
use App\Services\GoogleSheets;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Illuminate\Http\Response;
use Auth;

class LoginController extends Controller
{

    public function login(LoginRequest $request)
    {
        if ($token = $this->guard()->attempt($request->only('email', 'password'))) {
            $user =  $this->guard()->setToken($token)->authenticate();

            //Log the activity
            activity()
                ->performedOn($user)
                ->causedBy($user)
                ->withProperties(['user_platform' => $user->getFullPlatformDetails()])
                ->log('Login successful');

            return ResponseBuilder::asSuccess()
                ->withHttpCode(Response::HTTP_OK)
                ->withMessage(trans('auth.auth_success'))
                ->withData(['user' => $user, 'token' => $token])
                ->build();
        }
            return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                ->withHttpCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->withMessage(trans('auth.failed'))
                ->build();
    }

    /**
     * Invalidate the user token and log the user out.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function logout(Request $request)
    {
        //Log the activity
        activity()
            ->performedOn($request->user())
            ->causedBy($request->user())
            ->withProperties(['user_platform' => $request->user()->getFullPlatformDetails()])
            ->log('Logout successful');

        $this->guard()->logout();

        return ResponseBuilder::asSuccess()
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('Signed out successfully')
            ->build();
    }

    /**
     * Get the guard to be used during authentication.
     */
    protected function guard()
    {
        return Auth::guard('api_user');
    }
}
