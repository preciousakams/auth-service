<?php

namespace App\Http\Controllers\Administrator;

use App\Enums\User\RoleType;
use App\Exports\Administrator\UsersExport;
use App\Http\Requests\Administrator\User\MakeAdministratorRequest;
use App\Http\Requests\Administrator\User\RevokeAdministratorRequest;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class UserController extends Controller
{

    private $user;

    /**
     * UserController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        $users = $this->user->latest()->paginate(50);

        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('Users')
            ->withData(['users' => $users])
            ->build();
    }

    public function details(User $user)
    {
        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('Users')
            ->withData(['user' => $user])
            ->build();
    }


    public function export(Request $request)
    {
        //Log the activity
        activity()
            ->performedOn($request->user())
            ->causedBy($request->user())
            ->withProperties(['user_platform' => $request->user()->getFullPlatformDetails()])
            ->log('Administrators export successful');

        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function makeAdministrator(MakeAdministratorRequest $request)
    {
        $user = $this->user->where('email', $request->email)->firstOr();

        if ($user->hasRole(RoleType::SuperAdmin)) {
            return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                ->withHttpCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->withMessage('User is already an Administrator')
                ->build();
        }

        $user->assignRole(RoleType::SuperAdmin);
        $user->changed_password = false;
        $user->save();

        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('User is now an Administrator')
            ->build();
    }

    public function revokeAdministrator(RevokeAdministratorRequest $request)
    {
        $user = $this->user->where('email', $request->email)->firstOr();

        if (!$user->hasRole(RoleType::SuperAdmin)) {
            return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                ->withHttpCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->withMessage('User must be an Administrator')
                ->build();
        }

        $user->removeRole(RoleType::SuperAdmin);

        $user->syncPermissions([]);

        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('User administrative role has been revoked')
            ->build();
    }
}
