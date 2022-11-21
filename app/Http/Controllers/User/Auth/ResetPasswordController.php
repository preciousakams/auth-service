<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ];
    }

    protected function sendResetFailedResponse(Request $request, $response)
    {
        return ResponseBuilder::asError(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->withHttpCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->withMessage(trans($response))
            ->build();
    }

    protected function sendResetResponse(Request $request, $response)
    {
        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage(trans($response))
            ->build();
    }


    public function broker()
    {
        return Password::broker('users');
    }

    protected function guard()
    {
        return \Auth::guard('api_user');
    }
}
