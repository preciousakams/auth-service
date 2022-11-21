<?php

namespace App\Http\Controllers\Administrator\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administrator\Auth\LoginRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function login(LoginRequest $request)
    {
        if ($token = $this->guard()->attempt($request->only('liveizy_id', 'password'))) {
            $user =  $this->guard()->setToken($token)->authenticate();

            if ($user->is_admin) {
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
        }

        return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
            ->withHttpCode(Response::HTTP_BAD_REQUEST)
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

    /**
     * Get the login liveizy_id to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'liveizy_id';
    }
}
