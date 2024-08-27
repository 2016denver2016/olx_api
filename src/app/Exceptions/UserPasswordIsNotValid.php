<?php

namespace App\Exceptions;

class UserPasswordIsNotValid extends Exception
{
    /**
     * @var string
     */
    protected $message = 'Current user password is not valid';

    /**
     * @var int
     */
    protected $code = 400;
}
