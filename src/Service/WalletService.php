<?php

namespace App\Service;

use App\Repository\WalletRepository;
use Exception;

class WalletService
{
    private WalletRepository $walletRepository;

    public function __construct()
    {
        $this->walletRepository = new WalletRepository();
    }

    ////creation dyal wallet 
    public function createCurrentWalletForUser(int $userId): void
    {
        $month = (int) date('m');
        $year = (int) date('Y');
        $wallet = $this->walletRepository->findByUserMonthYear($userId, $month, $year);

        if (!$wallet) {
            $this->walletRepository->create($userId, $month, $year);
        }
    }

    /////update budget
    public function updateMonthlyBudget(int $userId, float $budget): void
    {
        $budget = (float) $budget;
        /// check l budget
        if ($budget <= 0) {
            throw new Exception("Le budget doit être un nombre positif");
        }

        $month = (int) date('m');
        $year = (int) date('Y');
        ///search wallet on db 
        $wallet = $this->walletRepository->findByUserMonthYear($userId, $month, $year);

        if (!$wallet) {
            throw new Exception("Wallet introuvable pour le mois en cours");
        }

        ///update budget
        $walletId = $wallet['id'];
        $this->walletRepository->updateBudget($walletId, $budget);
    }

    /////get monthly summary
    public function getMonthlySummary($userId)
    {
        $month = (int) date('m');
        $year = (int) date('Y');

        $wallet = $this->walletRepository->findByUserMonthYear($userId, $month, $year);

        if(!$wallet){
            throw new Exception("Wallet introuvable pour le mois en cours");
        }
        ///calcule 
        $budget = $wallet['budget'];
        $totalExpenses = 0;
        $remaining = $budget - $totalExpenses;

        return [
            'budget' => $budget,
            'totalExpenses' => $totalExpenses,
            'remaining' => $remaining
        ];
    }

    public function getCurrentWallet(int $userId)
    {
        $month = (int) date('m');
        $year = (int) date('Y');

        $wallet = $this->walletRepository->findByUserMonthYear($userId, $month, $year);

        if(!$wallet){
            throw new Exception("Wallet introuvable pour le mois en cours");
        }

        return $wallet;

    }
    
}

