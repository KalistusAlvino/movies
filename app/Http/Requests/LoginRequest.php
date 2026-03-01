<?php

namespace App\Http\Requests;

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
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => trans('validation.username_required'),
            'username.max' => trans('validation.username_max'),
            'password.required' => trans('validation.password_required'),
            'password.min' => trans('validation.password_min'),
        ];
    }
}
