<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\RegisterRequest;
use App\Models\IzyPay;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Database\ConnectionInterface as DB;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Auth;

class RegisterController extends Controller
{
    private User $user;
    private IzyPay $izyPay;
    private DB $db;



    /**
     * RegisterController constructor.
     * @param User $user
     * @param IzyPay $izyPay
     * @param DB $db
     */
    public function __construct(User $user, IzyPay $izyPay, DB $db)
    {
        $this->user = $user;
        $this->izyPay = $izyPay;
        $this->db = $db;
    }

    public function register(RegisterRequest $request)
    {
        $this->db->beginTransaction();

        $user = new $this->user;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->liveizy_id = $request->liveizy_id;
        $user->phone = $request->phone;
        $request->referral_liveizy ?
            $user->referral()->associate(User::where('liveizy_id', $request->referral_liveizy)->first()) : null;
        $user->save();

        $izyPay = new $this->izyPay;
        $izyPay->user()->associate($user);
        $izyPay->balance =  0;
        $izyPay->save();

        $this->db->commit();

        $user->sendEmailVerificationNotification();

        //Log the activity
        activity()
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties(['user_platform' => $user->getFullPlatformDetails()])
            ->log('Register successful');

        $token = Auth::guard('api_user')->login($user);

        return ResponseBuilder::asSuccess()
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage(trans('auth.register_success'))
            ->withData(['user' => $user, 'token' => $token])
            ->build();

    }
}
