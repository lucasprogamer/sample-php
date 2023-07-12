<?php

namespace App\Middleware;

use App\Entities\User;
use App\Exceptions\UserNotFoundException;
use Src\Exceptions\UnauthenticatedException;
use Src\Requester\Request;
use Src\Router\Middleware;

class AuthMiddleware implements Middleware
{
    public function handle(Request $request, $next)
    {
        if (!$request->has_authorization()) {
            throw new UnauthenticatedException();
        }
        try {
            $token = str_replace('Bearer ', '', $request->authorization());
            $user = User::findByAuthorizationCode($token);
            if ($user)
                return $next($request);
        } catch (UserNotFoundException $e) {
            throw new UnauthenticatedException();
        }
    }
}
