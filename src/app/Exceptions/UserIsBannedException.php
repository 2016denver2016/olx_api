<?php

namespace App\Exceptions;

class UserIsBannedException extends Exception
{
    /**
     * @var string
     */
    protected $message = 'User is banned!';

    /**
     * @var int
     */
    protected $code = 403;
}
