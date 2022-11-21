<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Role\AssignRoleRequest;
use App\Models\User;
use Illuminate\Database\ConnectionInterface as DB;
use Illuminate\Http\Response;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class RoleController extends Controller
{
    private DB $db;
    private User $user;

    /**
     * RoleController constructor.
     * @param DB $db
     * @param User $user
     */
    public function __construct(DB $db, User $user)
    {
        $this->db = $db;
        $this->user = $user;
    }

    public function assignRole(AssignRoleRequest $request)
    {
        $this->db->beginTransaction();

        if ($request->liveizy_id) {
            if ($request->liveizy_id === $request->user()->id) {
                return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                    ->withHttpCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                    ->withMessage('The Id cannot be your id')
                    ->build();
            }

            if (!$request->user()->hasRole(['Lanlord', 'Agent'])) {
                return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                    ->withHttpCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                    ->withMessage('You must be a Property Owner/Manager to assign a role to a user')
                    ->build();
            }

            if ($request->role != 'Tenant') {
                return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                    ->withHttpCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                    ->withMessage('You can only assign a Tenant role to another user')
                    ->build();
            }

            $user = $this->user->where('liveizy_id', $request->liveizy_id)->firstOrFail();

            if (!$user->hasRole($request->role)) {
                $user->assignRole($request->role);
            }
        } else {
            $request->user()->assignRole($request->role);

            //Log the activity
            activity()
                ->performedOn($request->user())
                ->causedBy($request->user())
                ->withProperties(['user_platform' => $request->user()->getFullPlatformDetails()])
                ->log("Became {$request->role}");
        }

        $this->db->commit();

        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('Congratulations, Role assigned successfully')
            ->withData(['user' => $request->user()])
            ->build();
    }

    public function removeRole(AssignRoleRequest $request)
    {
        if(empty($request->liveizy_id)) {
            return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                ->withHttpCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->withMessage('A liveizy_id is required')
                ->build();
        }

        if ($request->liveizy_id === $request->user()->liveizy_id) {
            return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                ->withHttpCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->withMessage('The Id cannot be your id')
                ->build();
        }

        $user = $this->user->where('liveizy_id', $request->liveizy_id)->firstOrFail();

        if ($user->hasRole($request->role)) {
            $user->removeRole($request->role);
        }

        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('Role removed successfully')
            ->withData(['user' => $user])
            ->build();
    }
}
