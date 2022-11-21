<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'date_of_birth' => 'nullable|string|before:today|date_format:Y-m-d',
            'gender' => 'nullable|in:male,female',
            'phone' => 'nullable',
            'state_of_origin' => 'nullable',
            'local_governement_area' => 'nullable'
        ];
    }
}
