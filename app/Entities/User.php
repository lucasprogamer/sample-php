<?php

namespace App\Entities;

use App\Entities\Entity;


class User extends Entity
{
    protected string $table = 'users';

    protected array $fillable = [
        'id' => 'int',
        'name' => 'string',
    ];
}
