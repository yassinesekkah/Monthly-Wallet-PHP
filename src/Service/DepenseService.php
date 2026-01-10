<?php

namespace App\Service;

use App\Repository\DepenseRepository;
use App\Service\WalletService;
use Exception;


class DepenseService
{

    public function createExpense(int $userId, string $title, float $amount, int $category_id, $date)
    {
        $walletService = new WalletService;
        $wallet = $walletService->getCurrentWallet($userId);
        $walletCalc = $walletService->getMonthlySummary($userId);

        $walletRemaining = $walletCalc['remaining'];

        ///title 
        if (empty($title)) {
            throw new Exception("Le titre de la dépense est obligatoire");
        }
        ///amount
        if ($amount <= 0) {
            throw new Exception("Le montant de la dépense doit être supérieur à zéro");
        }
        ///date
        $date = $date ?: date('Y-m-d');

        
        $category_id = (int) $category_id;
        ///category
        if ($category_id <= 0) {
            throw new Exception("La catégorie est obligatoire");
        }

        $amount = (float) $amount;
        if ($amount > $walletRemaining) {
            throw new Exception("Le montant de la dépense dépasse le solde restant");
        }

        ///l'appel dyal repository pour insert le depense
        $walletId = $wallet["id"];
        $depenseRepository = new DepenseRepository;
        $depenseRepository->insertExpense($walletId, $title, $amount, $category_id, $date);
    }
}
