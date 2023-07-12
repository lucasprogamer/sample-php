<?php

namespace Src\Router;

use Closure;
use Src\Requester\Request;

interface Middleware
{
    public function handle(Request $request, Closure $next);
}
