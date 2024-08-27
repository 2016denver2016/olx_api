<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\FormRequest;

class SendCodeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id'               => 'required|integer',
        ];
    }
}
