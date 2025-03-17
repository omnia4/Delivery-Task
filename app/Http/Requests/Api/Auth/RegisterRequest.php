<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email|unique:users',

            'mobile_number' => 'required|unique:users',
            'password' => 'required|min:6',
            'name' => 'required',
            'location' => 'required|string',
            'image_path' => 'required|image',
            'user_type' => 'in:user,delivery',
        ];
    }

    public function messages()
    {
        return [
            'mobile_number.required' => 'The phone number is required.',
        'mobile_number.unique' => 'The phone number has already been taken.',
        'password.required' => 'The password is required.',
        'password.min' => 'The password must be at least 6 characters.',
        'username.required' => 'The username is required.',
        'location.required' => 'The location is required.',
        'image_path.required' => 'The image is required.',
        'image_path.image' => 'The file must be an image.',
        ];
    }
}
