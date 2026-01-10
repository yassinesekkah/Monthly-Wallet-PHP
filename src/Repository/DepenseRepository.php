<?php

namespace App\Repository;

use App\Database;
use PDO;

class DepenseRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function insertExpense(
        int $walletId,
        string $titre,
        float $montant,
        int $categoryId,
        string $date,
        int $isAutomatic = 0
    ): void {
        $sql = "
            INSERT INTO depenses (
                wallet_id,
                category_id,
                titre,
                montant,
                `date`,
                is_automatic,
                created_at
            ) VALUES (
                :wallet_id,
                :category_id,
                :titre,
                :montant,
                :date,
                :is_automatic,
                NOW()
            )
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'wallet_id'    => $walletId,
            'category_id'  => $categoryId,
            'titre'        => $titre,
            'montant'      => $montant,
            'date'         => $date,
            'is_automatic' => $isAutomatic
        ]);
    }
}
