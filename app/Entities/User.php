<?php

namespace App\Entities;

use App\Entities\Entity;


class User extends Entity
{
    protected string $table = 'users';

    protected array $fillable = [
        'id' => 'int',
        'name' => 'string'
    ];

    protected array $fields = [
        'auth_code' => 'string',
    ];

    /**
     * thas is a sample implementation and should not be used directly use only for studies
     *
     * @return string
     */
    public function auth(): string
    {
        if (isset($this->id)) {
            $this->auth_code = md5(rand(1, 50000) . $this->name);
            if ($this->update())
                return $this->auth_code;
        }
        throw new \Exception('this user not exists', 401);
    }

    /**
     * thas is a sample implementation and should not be used directly use only for studies
     *
     * @param string $authorization
     * @return self
     */
    public static function findByAuthorizationCode($authorization)
    {
        $user = new User();
        return $user->findOne('auth_code', $authorization);
    }
}
