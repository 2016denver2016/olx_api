<?php

namespace App\Exceptions;

class UserNotActiveException extends Exception
{
    /**
     * @var string
     */
    protected $message = 'User email is not verified!';

    /**
     * @var int
     */
    protected $code = 403;
}
