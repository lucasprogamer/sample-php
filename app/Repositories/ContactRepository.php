<?php

namespace App\Repositories;

use App\Entities\Contact;
use PDO;
use Src\Database\Database;

class ContactRepository extends Repository implements RepositoryInterface
{

    private PDO $connection;

    public function __construct(private readonly Database $database)
    {
        $this->connection = $this->database->getConnection();
    }

    public function save(array $data): Contact
    {
        $contact = new Contact();
        $contact->fill($data);
        if ($contact->save()) {
            return $contact;
        }
        throw 'Contato não pode ser salvo';
    }

    public function get(int $id): Contact
    {
        $contact = new Contact();
        return $contact->get($id);
    }

    public function delete(int $id): bool
    {
        $contact = new Contact();
        return $contact->delete();
    }

    public function update(int $id, array $data): Contact
    {
        $contact = new Contact();
        $contact->get($id);
        $contact->fill($data);
        if ($contact->update()) {
            return $contact;
        }
        throw 'não foi possivel atualizar o contato';
    }

    public function index(): array
    {
        $result = $this->connection->query("SELECT * FROM contacts");
        $data = array();
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $contact = new Contact();
            $contact->fill([
                'id' => $row['id'],
                'name' => $row['name'],
                'number' => $row['number'],
                'has_whatsapp' => $row['has_whatsapp'],
                'email' => $row['email']
            ]);
            $data[] = $contact;
        }
        return $data;
    }
}
