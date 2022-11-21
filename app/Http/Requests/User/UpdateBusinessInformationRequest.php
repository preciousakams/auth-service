<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBusinessInformationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'business_name' => 'required',
            'business_registration_number' => 'required',
            'business_registration_address' => 'required',
            'business_phone' => 'required',
            'company_email' => 'required|email:filter',
            'state' => 'required',
            'lga' => 'required',
        ];
    }
}
