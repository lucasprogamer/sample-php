<?php

namespace App\Exceptions;


class UserNotFoundException extends \Exception
{
    protected $code = 404;
    protected $message = 'User not found';
}
