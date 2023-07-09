<?php

namespace Src\Router;


class Route
{
    protected static $router;
    protected static $container;

    private function __construct()
    {
    }

    protected static function getRouter()
    {
        if (empty(self::$router)) {
            self::$router = new Router();
        }
        return self::$router;
    }

    public static function setContainer($container)
    {
        self::$container = $container;
        Router::setContainer($container);
    }

    public static function post($pattern, $callback)
    {
        return self::getRouter()->post($pattern, $callback);
    }

    public static function get($pattern, $callback)
    {
        return self::getRouter()->get($pattern, $callback);
    }

    public static function put($pattern, $callback)
    {
        return self::getRouter()->put($pattern, $callback);
    }

    public static function delete($pattern, $callback)
    {
        return self::getRouter()->delete($pattern, $callback);
    }

    public static function resolve($request)
    {
        return self::getRouter()->resolve($request);
    }

    public static function translate($name, $params)
    {
        return self::getRouter()->translate($name, $params);
    }

    public static function group($prefix, $callback)
    {
        $router = self::getRouter();
        $router->mapper->prefix($prefix);
        $callback($router);
    }
}
