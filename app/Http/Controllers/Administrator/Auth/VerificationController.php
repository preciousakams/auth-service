<?php

namespace App\Http\Controllers\Administrator\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api_user');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verify(Request $request)
    {
        if (! hash_equals((string) $request->route('id'), (string) $request->user()->getKey())) {
            throw new AuthorizationException;
        }

        if (! hash_equals((string) $request->hash, sha1($request->user()->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        if ($request->user()->hasVerifiedEmail()) {
            return ResponseBuilder::asError(400)
                ->withHttpCode(Response::HTTP_BAD_REQUEST)
                ->withMessage('This email is already verified')
                ->build();
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        if ($response = $this->verified($request)) {
            return $response;
        }

        //Log the activity
        activity()
            ->performedOn($request->user())
            ->causedBy($request->user())
            ->withProperties(['user_platform' => $request->user()->getFullPlatformDetails()])
            ->log('Email Verification successful');

        return ResponseBuilder::asSuccess()
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('Email verified successfully!')
            ->build();
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return ResponseBuilder::asError(400)
                ->withHttpCode(Response::HTTP_BAD_REQUEST)
                ->withMessage('This email is already verified')
                ->build();
        }

        $request->user()->sendEmailVerificationNotification();

        //Log the activity
        activity()
            ->performedOn($request->user())
            ->causedBy($request->user())
            ->withProperties(['user_platform' => $request->user()->getFullPlatformDetails()])
            ->log('Request new email verification link');

        return ResponseBuilder::asError(400)
            ->withHttpCode(Response::HTTP_BAD_REQUEST)
            ->withMessage('A verification link has been sent to your email')
            ->build();
    }
}
