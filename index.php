<?php

use Src\Router\Route;
use Src\Router\Router;
use Src\Container\Injector;

error_reporting(E_ALL);
ini_set('display_errors', true);
session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit();
}

require __DIR__ . '/vendor/autoload.php';
try {
    $router = new Router();

    require __DIR__ . '/routes/api.php';

    $injector = new Injector();

    Route::setContainer($injector->container);
    resolve();
} catch (\Exception $e) {
    http_response_code((int) $e->getCode());
    echo $e->getMessage();
    exit;
}
