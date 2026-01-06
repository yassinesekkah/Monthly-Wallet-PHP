<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/database.php';

use App\Service\AuthService;
use App\Service\SecurityService;


$action = $_GET['action'] ?? null;

$controller = new AuthController();

switch ($action) {
    case 'register':
        $controller->register();
        break;

    case 'login':
        $controller->login();
        break;

    case 'logout':
        $controller -> logout();

    default:
        header('Location: ../auth/register.php');
        exit;
}

class AuthController
{   
    ///register
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../auth/register.php');
            exit;
        }
        if (
            !isset($_POST['csrf_token']) ||
            !SecurityService::verifyCSRFToken($_POST['csrf_token'])
        ) {
            $_SESSION['register_error'] = "Requête invalide (CSRF)";
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
        } catch (Exception $e) {
            $_SESSION['register_error'] = $e->getMessage();
            header('Location: ../auth/register.php');
            exit;
        }
    }
    ///login controller
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ../auth/login.php");
            exit;
        }
        if (
            !isset($_POST['csrf_token']) ||
            !SecurityService::verifyCSRFToken($_POST['csrf_token'])
        ) {
            $_SESSION['login_error'] = "Requête invalide (CSRF)";
            header('Location: ../auth/login.php');
            exit;
        }

        ///nakhdo input values
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        try {
            $authService = new AuthService();
            $user = $authService->login($email, $password);
            
            SecurityService::loginUser($user);

            header("Location: ../dashboard.php");
            exit;
        } catch (Exception $e) {
            $_SESSION['login_error'] = $e->getMessage();
            header('Location: ../auth/login.php');
            exit;
        }
    }

    ///logout
    public function logout(): void
    {
        SecurityService::logout();

        header("Location: ../auth/login.php");
        exit;
    }
}
