<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\FormRequest;

class RefreshRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'device_id' => 'required|string',
        ];
    }
}
