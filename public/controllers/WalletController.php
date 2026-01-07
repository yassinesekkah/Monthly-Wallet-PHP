<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/database.php';

use App\Service\WalletService;
use App\Service\SecurityService;

$action = $_GET['action'] ?? null;

$controller = new WalletController();

switch ($action) {
    case 'updateBudget':
        $controller->updateBudget();
        break;

    default:
        header('Location: ../dashboard.php');
        exit;
}

class WalletController
{
    public function updateBudget()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../dashboard.php');
            exit;
        }
        /// check login
        SecurityService::requireLogin();
        ////csrf check
        if (
            !isset($_POST['csrf_token']) ||
            !SecurityService::verifyCSRFToken($_POST['csrf_token'])
        ) {
            $_SESSION['register_error'] = "Requête invalide (CSRF)";
            header('Location: ../dashboard.php');
            exit;
        }
        ///input value
        $budget = $_POST['budget'] ?? null;
        $userId = $_SESSION['user_id'];

        try {
            $walletService = new WalletService;
            $walletService->updateMonthlyBudget($userId, $budget);

            $_SESSION['budget_success'] = "Budget mis à jour avec succès";
            header("Location: ../dashboard.php");
            exit;
        }
        catch (Exception $e) {
            $_SESSION['budget_error'] = $e->getMessage();
            header("Location: ../dashboard.php");
            exit;
        }
    }
}
