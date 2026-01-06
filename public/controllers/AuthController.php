<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/database.php';

use App\Service\AuthService;

$action = $_GET['action'] ?? null;

$controller = new AuthController();

switch ($action) {
    case 'register':
        $controller->register();
        break;

    default:
        header('Location: ../auth/register.php');
        exit;
}

class AuthController
{
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../auth/register.php');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        try {
            $authService = new AuthService();
            $authService->register($name, $email, $password);

            header('Location: ../auth/login.php');
            exit;
        } 
        catch (Exception $e) {
            $_SESSION['register_error'] = $e->getMessage();
            header('Location: ../auth/register.php');
            exit;
        }
    }
}
