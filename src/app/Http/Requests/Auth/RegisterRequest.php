<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email'                 => 'email|required_without:phone|unique:users,email',
            'password'              => 'required|confirmed|min:8|max:36',
            'password_confirmation' => 'required|same:password|min:8|max:36',
        ];
    }
}
