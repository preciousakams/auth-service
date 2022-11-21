<?php

namespace App\Http\Requests\Administrator\Permission;

use Illuminate\Foundation\Http\FormRequest;

class AssignPermissionRequest extends FormRequest
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
            'same_email' => $this->user()->email != $this->email
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
            'email' => 'required|email:filter|exists:users',
            'permission_name' => 'required|exists:permissions,name',
            'same_email' => 'accepted',
        ];
    }

    public function messages()
    {
        return[
            'same_email.accepted' => 'You can\'t assign yourself a permission',
        ];
    }


}
