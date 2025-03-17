<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'mobile_number' => 'required',
            'verification_code' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'mobile_number.required' => 'phone number required.',
            'verification_code.required' => 'verification code required.',
        ];
    }
}
