<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'mobile_number' => 'required',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'mobile_number.required' => 'phone number required',
            'password.required' => 'password required.',
        ];
    }
}
