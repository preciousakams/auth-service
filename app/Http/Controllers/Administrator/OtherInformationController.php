<?php

namespace App\Http\Controllers\Administrator;

use App\Enums\User\RoleType;
use App\Exports\Administrator\UsersExport;
use App\Http\Requests\Administrator\User\MakeAdministratorRequest;
use App\Http\Requests\Administrator\User\RevokeAdministratorRequest;
use App\Models\User;
use App\Models\OtherInformation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class OtherInformationController extends Controller
{

    private $user;
    private $otherInformation;

    /**
     * UserController constructor.
     * @param User $user
     * @param OtherInformation $otherInformation
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $otherInformation = $this->user->otherInformation;
    }

    public function details(User $user)
    {
        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('Users')
            ->withData(['otherInformation' => $user->otherInformation])
            ->build();
    } 
}