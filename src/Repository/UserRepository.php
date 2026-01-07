<?php

namespace App\Repository;

use App\Database;
use PDO;

class UserRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance() -> getConnection();
    }
    ///wach email deja kayen f db
    public function emailExists(string $email): bool
    {
        $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
        $stmt = $this -> db -> prepare ($sql);
        $stmt -> execute(['email' => $email]);
        return (bool) $stmt -> fetchColumn();
    }
    ///creation dyal new user
    public function create($name, $email, $password): int
    {
        $sql = "INSERT INTO users (name, email, password)
                VALUES (:name, :email, :password)";
        $stmt = $this -> db -> prepare($sql);
        $stmt -> execute([
            "name" => $name,
            "email" => $email,
            "password" => $password
        ]);
        $userId = $this->db->lastInsertId();
        return (int) $userId;
    }
    ///find by email method
    public function findByEmail(string $email): array|false
    {
        $sql = "SELECT id, name, email, password FROM users WHERE email = :email";
        $stmt = $this -> db -> prepare ($sql);
        $stmt -> execute (['email' => $email]);
        $result = $stmt -> fetch();
        return $result;
    }
}




