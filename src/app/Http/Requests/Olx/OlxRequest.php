<?php

namespace App\Http\Requests\Olx;

use App\Http\Requests\FormRequest;

class OlxRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'olx_url'        => 'required|url',
        ];
    }
}
