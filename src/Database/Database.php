<?php

namespace Src\Database;

use PDO;
use PDOException;

class Database
{
    private static $instance;
    private $pdo;

    public function __construct()
    {
        $database = __DIR__ . '/../../db/simple.db';
        try {
            $this->pdo = new PDO("sqlite:$database");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro ao conectar ao banco de dados: " . $e->getMessage());
        }
    }

    public static function getInstance($database)
    {
        if (!self::$instance) {
            self::$instance = new Database($database);
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}
