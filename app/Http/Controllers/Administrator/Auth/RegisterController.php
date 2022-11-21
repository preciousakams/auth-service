<?php

namespace App\Http\Controllers\Administrator\Auth;

use App\Enums\User\RoleType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administrator\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Database\ConnectionInterface as DB;
use Illuminate\Http\Response;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Str;

class RegisterController extends Controller
{
    private DB $db;
    private User $user;

    public function __construct(DB $db, User $user)
    {
        $this->middleware(['auth:api_user', 'changed.password', 'verified']);

        $this->db = $db;
        $this->user = $user;

    }

    public function register(RegisterRequest $request)
    {
        $this->db->beginTransaction();

        $password = Str::random(9);

        $administrator = new $this->user;
        $administrator->first_name = $request->first_name;
        $administrator->last_name = $request->last_name;
        $administrator->email = $request->email;
        $administrator->liveizy_id = $request->liveizy_id;
        $administrator->changed_password = false;
        $administrator->password = bcrypt($password);
        $administrator->email_verified_at = now();
        $administrator->assignRole(RoleType::SuperAdmin);
        $administrator->save();

        $this->db->commit();

        $administrator->sendNewPasswordNotification($password);
        //$administrator->sendEmailVerificationNotification();

        //Log the activity
        activity()
            ->performedOn($administrator)
            ->causedBy($request->user())
            ->withProperties(['user_platform' => $request->user()->getFullPlatformDetails()])
            ->log('Register successful');

        return ResponseBuilder::asSuccess()
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage(trans('auth.register_success'))
            ->build();
    }
}
