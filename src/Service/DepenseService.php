<?php

namespace App\Service;

use App\Service\WalletService;
use Exception;


class DepenseService
{

    public function createExpense(int $userId, string $title, float $amount, int $category_id, $date)
    {
        $walletService = new WalletService;
        $wallet = $walletService -> getCurrentWallet($userId);
        $walletCalc = $walletService -> getMonthlySummary($userId);

        $walletRemaining = $walletCalc['remaining'];

        ///title 
        if(empty($title)){
            throw new Exception ("Le titre de la dépense est obligatoire");
        }
        ///amount
        if($amount <= 0 ){
            throw new Exception ("Le montant de la dépense doit être supérieur à zéro");
        }

        ///hta terja3 mn ba3d matzid categories wdir check wach kayna f db 

        if($amount > $walletRemaining){
            throw new Exception("Le montant de la dépense dépasse le solde restant");
        }
        

        
    }
}
