<?php

namespace App\Entities;


class Contact extends Entity
{
    protected string $table = 'contacts';

    protected array $fillable = [
        'id' => 'int',
        'number' => 'string',
        'name' => 'string',
        'email' => 'string',
        'has_whatsapp' => 'boolean',
        'user_id' => 'int'
    ];
}
