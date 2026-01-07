<?php

namespace App\Service;

use App\Repository\WalletRepository;

class WalletService 
{
    private WalletRepository $walletRepository;

    public function __construct()
    {
        $this -> walletRepository = new WalletRepository();
    }

    public function createCurrentWalletForUser (int $userId): void
    {
        $month = (int) date('m');
        $year = (int) date('Y');
        $wallet = $this -> walletRepository -> findByUserMonthYear($userId, $month, $year);

        if(!$wallet){
            $this -> walletRepository -> create($userId, $month, $year);
        }
    }

}
