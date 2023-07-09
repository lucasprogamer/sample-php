<?php

namespace App\Repositories;

use App\Entities\Entity;



interface RepositoryInterface
{
    public function save(array $data): Entity;
    public function index(): array;
    public function delete(int $id): bool;
    public function update(int $id, array $data): Entity;
    public function get(int $id): Entity;
}
