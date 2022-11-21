<?php

namespace App\Http\Requests\User\Role;

use Illuminate\Foundation\Http\FormRequest;

class AssignRoleRequest extends FormRequest
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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'role' => ucfirst($this->role),
            'does_not_have_role' => !$this->user()->hasRole(ucfirst($this->role))
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
            'role' => 'required|in:Landlord,Agent,Tenant',
            'liveizy_id' => 'nullable|exists:users,liveizy_id',
            'does_not_have_role' => 'accepted'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'does_not_have_role.accepted' => 'You already have this role'
        ];
    }


}
