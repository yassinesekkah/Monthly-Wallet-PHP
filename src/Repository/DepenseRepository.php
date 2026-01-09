<?php

namespace App\Repository;

use app\Database;
use PDO;

class DepenseRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function insertExpense(int $walletId, string $title,float $amount,
                                                            int $categoryId,string $date): void
    {
        $sql = "
            INSERT INTO expenses (wallet_id, category_id, title, amount, date, created_at) 
            VALUES ( :wallet_id, :category_id, :title, :amount,:date,NOW())
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'wallet_id'   => $walletId,
            'category_id' => $categoryId,
            'title'       => $title,
            'amount'      => $amount,
            'date'        => $date,
        ]);
    }
}
