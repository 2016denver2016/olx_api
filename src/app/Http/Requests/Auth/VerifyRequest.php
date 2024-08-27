<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\FormRequest;

class VerifyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email_verify_code' => 'required|string',
        ];
    }
}
