<?php

namespace App\Http\Controllers\User;

use App\Models\BusinessInformation;
use App\Models\OtherInformation;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateBusinessInformationRequest;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Database\ConnectionInterface as DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class ProfileController extends Controller
{
    private DB $db;
    private BusinessInformation $businessInformation;
    private OtherInformation $otherInformation;

    public function __construct(DB $db, BusinessInformation $businessInformation, OtherInformation $otherInformation)
    {
        $this->db = $db;
        $this->businessInformation = $businessInformation;
        $this->otherInformation = $otherInformation;
    }

    public function logs(Request $request)
    {
        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('User Logs')
            ->withData(['logs' => $request->user('api_user')->activities])
            ->build();
    }

    public function getProfile()
    {
         return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('User Details')
            ->withData(['user' => auth()->user()])
            ->build();
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        if(!empty($request->bvn)) {
            //TODO Validate BVN
        }

        $this->db->beginTransaction();

        $user = $request->user();
        $user->first_name = $request->first_name ?? $user->first_name;
        $user->last_name = $request->last_name ?? $user->last_name;
        $user->gender = $request->gender ?? $user->gender;
        $user->date_of_birth = $request->date_of_birth ?? $user->date_of_birth;
        $user->phone = $request->phone ?? $user->phone;
        $user->state_of_origin = $request->state_of_origin ?? $user->state_of_origin;
        $user->local_governement_area = $request->local_governement_area ?? $user->local_governement_area;
        $user->profile_image = $request->profile_image ?? $user->profile_image;
        $user->bvn = $request->bvn ?? $user->bvn;
        $user->save();

        $this->db->commit();

        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('Profile updated successfully')
            ->withData(['user' => $user])
            ->build();
    }

    public function updateBusinessInformation(UpdateBusinessInformationRequest $request)
    {
        $this->db->beginTransaction();

        $businessInformation = $this->businessInformation->firstOrNew(['user_id' => $request->user()->id]);
        $businessInformation->business_name = $request->business_name;
        $businessInformation->business_registration_number = $request->business_registration_number;
        $businessInformation->business_registration_address = $request->business_registration_address;
        $businessInformation->business_phone = $request->business_phone;
        $businessInformation->company_email = $request->company_email;
        $businessInformation->state = $request->state;
        $businessInformation->lga = $request->lga;
        $businessInformation->save();

        $this->db->commit();

        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('Business information updated successfully')
            ->withData([
                'business_information' =>  $request->user()->businessInformation
            ])
            ->build();
    }

    public function getBusinessInformation()
    {
        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('User Details')
            ->withData([
                'business_information' => auth()->user()->businessInformation
            ])
            ->build();
    }

    public function updateOtherInformation(Request $request)
    {
        $this->db->beginTransaction();

        $otherInformation = $this->otherInformation->firstOrNew(['user_id' => $request->user()->id]);
        foreach ($request->toArray() as $key => $data) {
            if(!empty($data)) {
                $otherInformation->{$key} = $data;
            }
        }
        $otherInformation->save();

        $this->db->commit();

        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('Other information updated successfully')
            ->withData([
                'other_information' =>  $request->user()->otherInformation
            ])
            ->build();
    }

    public function getOtherInformation()
    {
        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('User Details')
            ->withData([
                'other_information' => auth()->user()->otherInformation
            ])
            ->build();
    }

    public function validateUserId(User $user)
    {
        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('User Liveizy Id is valid')
            ->withData(['user' => [
                'liveizy_id' =>  $user->liveizy_id,
                'name' => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
            ]])
            ->build();
    }

    public function getDetails(User $user)
    {
        return ResponseBuilder::asSuccess(Response::HTTP_OK)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('User details')
            ->withData([ 'user' => [
                'profile' => $user->makeHidden('referral_id', 'is_email_verified','created_at','updated_at','roles','role','changed_password')->withoutRelations('businessInformation','other_information'),
                'business_information' => is_object($user->businessInformation) ? $user->businessInformation->makeHidden('created_at','updated_at','user_id') : null,
                'other_information' => is_object($user->otherInformation) ? $user->otherInformation->makeHidden('created_at','updated_at','user_id') : null,
                //'other_information' => $user->->makeHidden('created_at','updated_at','user_id'),
            ]
            ])->build();
    }
}
