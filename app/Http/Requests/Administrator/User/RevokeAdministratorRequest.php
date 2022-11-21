<?php

namespace App\Http\Requests\Administrator\User;

use Illuminate\Foundation\Http\FormRequest;

class RevokeAdministratorRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $this->merge([
            'developer_email' => $this->email != config('app.developer_email')
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' =>  'required|email|exists:users',
            'developer_email' => 'accepted'
        ];
    }

    public function messages()
    {
        return [
            'developer_email.accepted' => 'Invalid email provided'
        ];
    }
}
