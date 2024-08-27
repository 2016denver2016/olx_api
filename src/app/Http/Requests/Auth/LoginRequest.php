<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email'      => 'string',
            'password'   => 'required|string|min:8',
        ];
    }
}
