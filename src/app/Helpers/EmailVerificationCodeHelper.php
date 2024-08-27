<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class EmailVerificationCodeHelper
{
    public function generate(): string
    {
        return Str::random(32);
    }
}
