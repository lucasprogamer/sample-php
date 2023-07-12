<?php

namespace Src\Router;

use Exception;
use Src\Router\MiddlewareResolver;

class Router
{
    public $mapper;
    protected $dispatcher;
    protected static $container;
    protected MiddlewareResolver $middlewareResolver;

    public function __construct()
    {
        $this->dispatcher = new Dispatcher();
        $this->mapper = new RouterMapper();
        $this->middlewareResolver = new MiddlewareResolver();
    }

    public static function setContainer($container)
    {
        self::$container = $container;
    }

    public function get($pattern, $callback)
    {
        $this->mapper->add('GET', $pattern, $callback);
        return $this;
    }

    public function post($pattern, $callback)
    {
        $this->mapper->add('POST', $pattern, $callback);
        return $this;
    }

    public function put($pattern, $callback)
    {
        $this->mapper->add('PUT', $pattern, $callback);
        return $this;
    }

    public function delete($pattern, $callback)
    {
        $this->mapper->add('DELETE', $pattern, $callback);
        return $this;
    }

    public function find($requestType, $pattern)
    {
        return $this->mapper->where($requestType, $pattern);
    }

    protected function dispatch($route, $params)
    {
        $callback = $route->callback;
        if ($callback instanceof \Closure) {
            return $callback(...$params);
        } elseif (is_array($callback)) {
            [$controller, $method] = array_values($callback);
            $controller = self::$container->resolve($controller);
            return $controller->{$method}(...$params);
        }

        throw new Exception('Invalid callback provided for the route.', 500);
    }

    public function resolve($request)
    {
        $route = $this->find($request->method(), $request->uri());
        if ($route) {
            $this->middlewaresResolve($route, $request);
            $params = $route->values ? $this->getValues($request->uri(), $route->values) : [];
            return $this->dispatch($route, $params);
        }
        throw new Exception('Page not found', 404);
    }

    private function middlewaresResolve($route, $request)
    {
        $this->setMiddlewares($route);
        $this->middlewareResolver->resolve($request);
    }

    private function setMiddlewares($route)
    {
        if (array_key_exists('middleware', $route->callback)) {
            $middleware = $route->callback['middleware'];
            if (is_array($middleware)) {
                foreach ($middleware as $m) {
                    $this->middlewareResolver->add($m);
                }
            }
            if (is_string($middleware)) {
                $this->middlewareResolver->add($middleware);
            }
        }
    }

    protected function getValues($pattern, $positions)
    {
        $result = [];
        $pattern = array_filter(explode('/', $pattern));

        foreach ($pattern as $key => $value) {
            if (in_array($value, $positions)) {
                $result[array_search($value, $positions)] = $value;
            }
        }

        return $result;
    }

    public function translate($name, $params)
    {
        $pattern = $this->mapper->isThereAnyHow($name);

        if ($pattern) {
            $protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
            $server = $_SERVER['SERVER_NAME'] . '/';
            $uri = [];

            foreach (array_filter(explode('/', $_SERVER['REQUEST_URI'])) as $key => $value) {
                if ($value == 'public') {
                    $uri[] = $value;
                    break;
                }
                $uri[] = $value;
            }
            $uri = implode('/', array_filter($uri)) . '/';

            return $protocol . $server . $uri . $this->mapper->convert($pattern, $params);
        }

        return false;
    }

    public static function resolveMiddlewares()
    {
    }
}
