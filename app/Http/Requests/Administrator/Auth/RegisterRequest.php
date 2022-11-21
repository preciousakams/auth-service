<?php

namespace App\Http\Requests\Administrator\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Str;

class RegisterRequest extends FormRequest
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
            'liveizy_id' => "ADM" . strtoupper( Str::random(3) . time())
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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email:filter|unique:administrators',
            'liveizy_id' => 'required'
        ];
    }
}
