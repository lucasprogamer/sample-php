<?php

namespace Src\Container;

use App\Controllers\ContactController;
use Src\Database\Database;
use Src\Container\ContainerDI;
use App\Controllers\UserController;
use App\Repositories\ContactRepository;
use App\Repositories\UserRepository;

class Injector
{
    public ContainerDI $container;

    public function __construct()
    {
        $this->container = new ContainerDI();
        $this->handler();
    }


    private function handler()
    {

        $this->container->bind(UserRepository::class, function () {
            $database = new Database();
            return new UserRepository($database);
        });
        $this->container->bind(UserController::class, function () {
            $repository = $this->container->resolve(UserRepository::class);
            return new UserController($repository);
        });

        $this->container->bind(ContactRepository::class, function () {
            $database = new Database();
            return new ContactRepository($database);
        });
        $this->container->bind(ContactController::class, function () {
            $repository = $this->container->resolve(ContactRepository::class);
            return new ContactController($repository);
        });
    }
}
