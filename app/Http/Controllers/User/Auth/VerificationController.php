<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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

    private User $user;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verify(Request $request)
    {
        $user = $this->user::findOrFail($request->route('id'));
        if (! hash_equals((string) $request->route('id'), (string) $user->getKey())) {
            return redirect(config('app.frontend_url'));
        }

        if (! hash_equals((string) $request->hash, sha1($user->getEmailForVerification()))) {
            return redirect(config('app.frontend_url'));
        }

        if ($user->hasVerifiedEmail()) {
            return redirect(config('app.frontend_url'));
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        if ($this->verified($request)) {
            return redirect(config('app.frontend_url'));
           // return $response;
        }

        //Log the activity
        activity()
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties(['user_platform' => $user->getFullPlatformDetails()])
            ->log('Email Verification successful');

        if ($request->ajax()) {
            return ResponseBuilder::asSuccess(Response::HTTP_OK)
                ->withHttpCode(Response::HTTP_OK)
                ->withMessage('Email verified successfully')
                ->build();
        }
        return redirect(config('app.frontend_url'));
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

        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('A verification link has been sent to your email')
            ->build();
    }
}
