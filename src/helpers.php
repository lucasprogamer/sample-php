<?php

use Src\Router\Route;
use Src\Requester\Request;

if (!function_exists('request')) {
    function request()
    {
        return new Request;
    }
}
if (!function_exists('resolve')) {
    function resolve($request = null)
    {
        $request = $request ?? request();
        return Route::resolve($request);
    }
}
if (!function_exists('route')) {
    function route($name, $params = null)
    {
        return Route::translate($name, $params);
    }
}
if (!function_exists('validateBrackets')) {
    function validateBrackets($string)
    {
        $stack = [];
        $bracketPairs = ['()' => true, '{}' => true, '[]' => true];

        foreach (str_split($string) as $char) {
            if (in_array($char, ['(', '{', '['])) {
                $stack[] = $char;
            }

            if (in_array($char, [')', '}', ']'])) {
                $lastOpenBracket = array_pop($stack);

                if (!isset($bracketPairs[$lastOpenBracket . $char])) {
                    return false;
                }
            }
        }

        return empty($stack);
    }
}
