<?php

namespace App\Http\Controllers\Administrator;

use App\Models\User;
use App\Enums\User\RoleType;
use App\Exports\Administrator\AdministratorsExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class AdministratorController extends Controller
{
    private $user;

    /**
     * AdministratorController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        $administrators = $this->user->role(RoleType::SuperAdmin)->get();

        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('Administrators List')
            ->withData(['administrators' => $administrators])
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

        return Excel::download(new AdministratorsExport, 'administrators.xlsx');
    }

}
