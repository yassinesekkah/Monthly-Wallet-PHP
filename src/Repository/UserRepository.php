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
    public function create($name, $email, $password)
    {
        $sql = "INSERT INTO users (name, email, password)
                VALUES (:name, :email, :password)";
        $stmt = $this -> db -> prepare($sql);
        $stmt -> execute([
            "name" => $name,
            "email" => $email,
            "password" => $password
        ]);
    }
    ///find by email method
    public function findByEmail(string $email): array|false
    {
        $sql = "SELECT id, email, password FROM users WHERE email = :email";
        $stmt = $this -> db -> prepare ($sql);
        $stmt -> execute (['email' => $email]);
        $result = $stmt -> fetch();
        return $result;
    }
}




