<?php

namespace App\Repositories;

use App\Entities\Entity;

abstract class Repository implements RepositoryInterface
{

    public function save(array $data): Entity
    {
        return new Entity($data);
    }

    public function index(): array
    {
        return [];
    }

    public function delete(int $id): bool
    {
        return false;
    }

    public function update(int $id, array $data): Entity
    {
        return new Entity($data);
    }

    public function get(int $id): Entity
    {
        return new Entity([]);
    }
}
