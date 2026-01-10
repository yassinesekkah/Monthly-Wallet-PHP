<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/database.php';


use App\Service\SecurityService;
use App\Service\DepenseService;

$action = $_GET['action'] ?? null;

$controller = new DepenseController();

switch ($action) {
    case 'store':
        $controller->store();
        break;

    default:
        header('Location: ../dashboard.php');
        exit;
}

class DepenseController
{
    public function store()
    {
        ///check if submit is POST or not
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
        ///input values
        $title = trim($_POST['title'] ?? '');
        $amount = trim($_POST['amount'] ?? '');
        $category_id = trim($_POST['category_id'] ?? '');
        $date = trim($_POST['date'] ?? '');
        $userId = $_SESSION['user_id'];

        try{
            $depenseService = new DepenseService;
            $depenseService -> createExpense($userId, $title, $amount, $category_id, $date);
            $_SESSION['depense_success'] = " Dépense ajoutée avec succès";
            header("Location: ../dashboard.php");
            exit;
        }
        catch(Exception $e){
             $_SESSION['depense_error'] = $e->getMessage();
            header("Location: ../dashboard.php");
            exit;
        }
    }
}
