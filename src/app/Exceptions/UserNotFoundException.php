<?php

namespace App\Exceptions;

class UserNotFoundException extends Exception
{
    /**
     * @var string
     */
    protected $message = 'Invalid credentials!';

    /**
     * @var int
     */
    protected $code = 401;
}
