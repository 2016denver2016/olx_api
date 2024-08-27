<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest as Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class FormRequest extends Request
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @throws ValidationException
     */
    public function validated(): array
    {
        $validator = Validator::make($this->request->all(), $this->rules());

        if ($validator->validate()) {
            return $this->only(array_keys($this->rules()));
        }

        return [];
    }
}
