<?php

namespace Src\Exceptions;

class UnauthenticatedException extends \Exception
{
    protected $code = 401;
    protected $message = 'Unauthenticated';
}
