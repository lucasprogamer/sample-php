<?php

namespace App\Repositories;

use PDO;
use App\Entities\User;
use Src\Database\Database;

class UserRepository extends Repository implements RepositoryInterface
{
    private PDO $connection;

    public function __construct(private readonly Database $database)
    {
        $this->connection = $this->database->getConnection();
    }

    public function save(array $data): User
    {
        $user = new User();
        $user->fill($data);
        if ($user->save()) {
            return $user;
        }
        throw 'Pessoa não pode ser salva';
    }

    public function get(int $id): User
    {
        $user = new User();
        return $user->get($id);
    }

    public function delete(int $id): bool
    {
        $user = new User();
        return $user->delete();
    }

    public function update(int $id, array $data): User
    {
        $user = new User();
        $user->get($id);
        $user->fill($data);
        if ($user->update()) {
            return $user;
        }
        throw 'não foi possivel atualizar a pessoa';
    }

    public function index(): array
    {
        $result = $this->connection->query("SELECT * FROM person");
        $data = array();
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = new User();
            $user->fill([
                'id' => $row['id'],
                'name' => $row['name'],
                'contact_id' => $row['contact_id']
            ]);
            $data[] = $user;
        }
        return $data;
    }

    public function getWithContacts(int $id): array
    {
        $query = "SELECT
                users.id,
                users.name,
                contacts.id as contact_id,
                contacts.name as contact_name,
                contacts.number as contact_number,
                contacts.has_whatsapp,
                contacts.email
            FROM
                users
            LEFT JOIN contacts ON
                contacts.user_id = users.id
            WHERE
                users.id = :id";
        $statement = $this->connection->prepare($query);
        $statement->bindValue(':id', $id);
        $statement->execute();
        $user = null;
        $contacts = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            if (!$user) {
                $user =  ['id' => $row['id'], 'name' => $row['name']];
            }
            if (!is_null($row['contact_id'])) {
                $contacts[] = [
                    'id' => $row['contact_id'],
                    'name' => $row['contact_name'],
                    'number' => $row['contact_number'],
                    'has_whatsapp' => $row['has_whatsapp'],
                    'email' => $row['email']
                ];
            }
        }
        $user['contacts'] = $contacts;
        return $user;
    }

    public function findByName(string $name)
    {
        $query = "SELECT * FROM users WHERE name = :id";
        $statement = $this->connection->prepare($query);
        $statement->bindValue(':id', $name);
        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $user = new User();
        $user->fill($row);
        return $user;
    }
}
