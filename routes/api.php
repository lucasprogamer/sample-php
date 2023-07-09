<?php

use App\Controllers\ContactController;
use App\Controllers\UserController;
use Src\Router\Route;

Route::get('/', function () {
    echo 'Hello, world!';
});

Route::get('/users', ['controller' =>  UserController::class, 'method' => 'index']);
Route::post('/users', ['controller' =>  UserController::class, 'method' => 'create']);
Route::get('/users/{id}/contacts', ['controller' =>  UserController::class, 'method' => 'getWithContacts']);
Route::get('/users/{name}/name', ['controller' =>  UserController::class, 'method' => 'findByName']);
Route::put('/users/{id}', ['controller' =>  UserController::class, 'method' => 'update']);
Route::delete('/users/{id}', ['controller' =>  UserController::class, 'method' => 'delete']);

Route::get('/contacts', ['controller' =>  ContactController::class, 'method' => 'index']);
Route::get('/contacts/{id}', ['controller' =>  ContactController::class, 'method' => 'get']);
Route::post('/contacts', ['controller' =>  ContactController::class, 'method' => 'create']);
Route::put('/contacts/{id}', ['controller' =>  ContactController::class, 'method' => 'update']);
Route::delete('/contacts/{id}', ['controller' =>  ContactController::class, 'method' => 'delete']);
