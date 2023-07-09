<?php

namespace App\Entities;

use PDO;
use Src\Database\Database;

class Entity extends \stdClass
{
    private Database $database;
    private PDO $connection;
    protected string $table = '';
    protected array $fillable = [];

    public function __construct()
    {
        $this->database = new Database();
        $this->connection = $this->database->getConnection();
    }

    public function save(): self | bool
    {
        $columns = [];
        $values = [];
        $placeholders = [];

        foreach ($this->fillable as $attribute => $type) {
            if (property_exists($this, $attribute)) {
                $columns[] = $attribute;
                $values[] = $this->$attribute;
                $placeholders[] = ':' . $attribute;
            }
        }
        $table_name = $this->table;
        $query = "INSERT INTO $table_name (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ");";
        $statement = $this->connection->prepare($query);
        foreach ($columns as $index => $column) {
            $statement->bindValue($placeholders[$index], $values[$index]);
        }

        if ($statement->execute()) {
            $this->id = $this->connection->lastInsertId();
            return $this;
        }
        return false;
    }


    public function update(): bool
    {
        $columns = [];
        $values = [];

        foreach ($this->fillable as $attribute => $type) {
            if (property_exists($this, $attribute)) {
                $columns[] = $attribute . ' = :' . $attribute;
                $values[':' . $attribute] = $this->$attribute;
            }
        }
        $table_name = $this->table;

        $query = "UPDATE $table_name SET " . implode(', ', $columns) . " WHERE id = :id";
        $statement = $this->connection->prepare($query);
        $values[':id'] = $this->id;

        return $statement->execute($values);
    }

    public function delete()
    {
        $table_name = $this->table;

        $query = "DELETE FROM $table_name WHERE id = :id";
        $statement = $this->connection->prepare($query);
        $statement->bindValue(':id', $this->id);

        return  $statement->execute();
    }

    public function get($id): self
    {
        $table_name = $this->table;

        $query = "SELECT * FROM $table_name WHERE id = :id";
        $statement = $this->connection->prepare($query);
        $statement->bindValue(':id', $id);
        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $this->fill($row);
        return $this;
    }


    public function fill($attributes)
    {
        foreach ($attributes as $key => $value) {
            if (array_key_exists($key, $this->fillable)) {
                $this->__set($key, $value);
            }
        }
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value)
    {
        $this->$property = $value;
    }
}
