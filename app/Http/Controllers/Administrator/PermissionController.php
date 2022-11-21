<?php

namespace App\Http\Controllers\Administrator;

use App\Enums\User\RoleType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administrator\Permission\AssignPermissionRequest;
use App\Http\Requests\Administrator\Permission\CreatePermissionRequest;
use App\Http\Requests\Administrator\Permission\RevokePermissionRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\ConnectionInterface as DB;
use App\Models\Permission;
use App\Models\User;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class PermissionController extends Controller
{
    private DB $db;
    private Permission $permission;
    private User $user;

    /**
     * PermissionController constructor.
     * @param DB $db
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(DB $db, Permission $permission, User $user)
    {
        $this->db = $db;
        $this->permission = $permission;
        $this->user = $user;
    }

    public function index(Request $request)
    {
        $permissions = $this->permission->newQuery();

        if ($request->name) {
            $permissions = $permissions->where('name', 'like', $request->name .'%');
        }

        $permissions = $permissions->get();

        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('Permissions')
            ->withData(['permissions' => $permissions])
            ->build();
    }

    public function create(CreatePermissionRequest $request)
    {
        $this->db->beginTransaction();

        $permission = new $this->permission();
        $permission->name = $request->name;
        $permission->guard_name = 'api_user';
        $permission->save();

        $admin = $this->user->where('email', config('app.developer_email'))->firstOrFail();
        $admin->givePermissionTo($permission->name);
        $admin->save();

        $this->db->commit();

        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('Permission created successfully')
            ->build();
    }

    public function assign(AssignPermissionRequest $request)
    {
        $user = $this->user->where('email', $request->email)->first();

        if (!$user->hasRole(RoleType::SuperAdmin)) {
            return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                ->withHttpCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->withMessage('User must be an administrator')
                ->build();
        }

        if (!$request->user()->hasPermissionTo($request->permission_name)) {
            return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                ->withHttpCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->withMessage('You can\'t assign this permission to a user')
                ->build();
        }

        if (!$user->hasPermissionTo($request->permission_name)) {
            $user->givePermissionTo($request->permission_name);
        }

        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('Permission assigned successfully')
            ->build();
    }

    public function revoke(RevokePermissionRequest $request)
    {
        $user = $this->user->where('email', $request->email)->first();

        if (!$user->hasRole(RoleType::SuperAdmin)) {
            return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                ->withHttpCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->withMessage('User must be an administrator')
                ->build();
        }

        if (!$request->user()->hasPermissionTo($request->permission_name)) {
            return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                ->withHttpCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->withMessage('You can\'t assign this permission to a user')
                ->build();
        }

        if (!$user->hasPermissionTo($request->permission_name)) {
            $user->revokePermissionTo($request->permission_name);
        }

        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('Permission revoked successfully')
            ->build();
    }

    public function userPermissions(User $user)
    {
        if (!$user->hasRole(RoleType::SuperAdmin)) {
            return ResponseBuilder::asError(SOMETHING_WENT_WRONG)
                ->withHttpCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->withMessage('User must be an administrator')
                ->build();
        }

        $permissions = $user->getPermissionNames();

        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('Permissions')
            ->withData([
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email
                ],
                'permissions' => $permissions
            ])
            ->build();
    }

    public function delete(Request $request, Permission $permission)
    {
        $this->db->beginTransaction();

        $permission->delete();

        $this->db->commit();

        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('Permission deleted successfully')
            ->build();
    }
}
