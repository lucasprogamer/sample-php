<?php

namespace Src\Router;

class  MiddlewareResolver
{
    protected static $middlewares = [];

    public static function add($middleware)
    {
        self::$middlewares[] = $middleware;
    }

    public static function resolve($request)
    {
        foreach (self::$middlewares as $middleware) {
            $instance = new $middleware();
            if ($instance instanceof Middleware) {
                $request = $instance->handle($request, function ($request) {
                    return $request;
                });
            }
        }
    }
}
