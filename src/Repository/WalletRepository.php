<?php

namespace App\Repository;

use App\Database;
use DateTime;
use PDO;

class WalletRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByUserMonthYear(int $userId, int $month, int $year)
    {
        $sql = "SELECT * FROM wallets
            WHERE user_id = :user_id
              AND month = :month
              AND year = :year";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'month'   => $month,
            'year'    => $year
        ]);

        return $stmt->fetch();
    }

    public function create(int $userId, int $month, int $year): void
    {
        $sql = "INSERT INTO wallets (user_id, budget, month, year)
                VALUES (:user_id, :budget, :month, :year)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'budget' => 0,
            'month' => $month,
            'year' => $year,
        ]);
    }

    public function updateBudget(int $walletId, float $budget): void
    {
        $sql = "UPDATE wallets SET budget = :budget WHERE id = :walletId";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'budget' => $budget,
            'walletId' => $walletId
        ]);
    }
}
