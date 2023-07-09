<?php

namespace Src\Router;

class RouterMapper
{
    protected $routes = [];
    protected $routeNames = [];
    protected $prefix = '';

    public function add($requestType, $pattern, $callback)
    {
        $this->validateRequestType($requestType);

        if (is_array($pattern)) {
            $settings = $this->parseRouteSettings($pattern);
            $pattern = $settings['route'];
        } else {
            $settings = [];
        }

        $values = $this->extractRouteValues($pattern);
        $pattern = $this->prefix . $pattern;

        $this->routes[$requestType][$this->definePattern($pattern)] = [
            'callback' => $callback,
            'values' => $values,
            'namespace' => $settings['namespace'] ?? null
        ];
        if (isset($settings['as'])) {
            $this->routeNames[$settings['as']] = $pattern;
        }
        return $this;
    }

    public function where($request_type, $pattern)
    {
        $pattern_sent = $this->sanitizeUri($pattern);
        $routes = $this->routes[$request_type] ?? [];
        foreach ($routes as $route_pattern => $route) {
            if (preg_match($route_pattern, $pattern_sent, $matches)) {
                return (object) ['callback' => $route['callback'], 'values' => $matches];
            }
        }

        return false;
    }

    public function isThereAnyHow($name)
    {
        return $this->routeNames[$name] ?? false;
    }

    public function prefix($prefix)
    {
        $this->prefix = trim($prefix, '/') . '/';
    }

    protected function validateRequestType($request_type)
    {
        if (!in_array($request_type, ['GET', 'POST', 'PUT', 'DELETE'])) {
            throw new \Exception('Tipo de requisição não implementado');
        }
    }

    protected function sanitizeUri($uri)
    {
        return implode('/', array_filter(explode('/', $uri)));
    }

    protected function definePattern($pattern)
    {
        $pattern = implode('/', array_filter(explode('/', $pattern)));
        $pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';

        if (preg_match("/\{([A-Za-z0-9_\-]+)\}/", $pattern)) {
            $pattern = preg_replace("/\{([A-Za-z0-9_\-]+)\}/", "([A-Za-z0-9_\-]+)", $pattern);
        }
        return $pattern;
    }

    protected function parseRouteSettings(array $pattern)
    {
        $result['route'] = $pattern['route'] ?? null;
        $result['as'] = $pattern['as'] ?? null;
        $result['namespace'] = $pattern['namespace'] ?? null;

        return $result;
    }

    protected function extractRouteValues($pattern)
    {
        $values = [];
        preg_match_all("/\{([A-Za-z0-9\_\-]{1,})\}/", $pattern ?? '', $matches);
        if (isset($matches[1])) {
            $values = $matches[1];
        }
        return $values;
    }

    public function convert($pattern, $params)
    {
        foreach ($params as $key => $value) {
            $pattern = str_replace("{{$key}}", $value, $pattern);
        }

        return $pattern;
    }
}
