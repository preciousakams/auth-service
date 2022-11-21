<?php

namespace App\Http\Requests\User\Auth;

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

    public function prepareForValidation()
    {
        $this->merge([
            'liveizy_id' => $this->randomString(),
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
            'first_name' => 'required|alpha',
            'last_name' => 'required|alpha',
            'email' => 'required|email:filter|max:255|unique:users|not_regex:/^[A-Za-z0-9\.]*@(liveizy)[.](com)$/',
            'password' => 'required|string|min:5|confirmed',
            'referral_liveizy' => 'nullable|exists:users,liveizy_id',
            'phone' => 'required|numeric|unique:users',
        ];
    }

    public function messages()
    {
        return [
          'referral_liveizy.exists' => 'Invalid referral code'
        ];
    }

    private function randomString() {

        $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

        mt_srand((double)microtime()*1000000);

        $random = '';

        for ($i = 0; $i < 3; $i++) {
            $random .= $characters[mt_rand() % strlen($characters)];
        }

        return 'U' . Str::limit($random . time(),7,'');
    }

}
