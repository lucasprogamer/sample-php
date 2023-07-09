<?php

namespace Src\Router;


class Dispatcher
{

    public function dispatch($callback, $params, $controller, $method)
    {
        if ($callback instanceof \Closure) {
            return $callback(...$params);
        } elseif (is_callable([$controller, $method])) {
            return $controller->{$method}(...$params);
        }
        throw new \Exception('Invalid callback provided for the route.');
    }
}
