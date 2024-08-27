<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\User;
use Illuminate\Queue\SerializesModels;

class ResetPasswordEvent
{
    use SerializesModels;

    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
