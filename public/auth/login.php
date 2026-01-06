<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once '../../config/database.php';

use App\Service\SecurityService;

$csrfToken = SecurityService::generateCSRFToken();

// Récupérer message d'erreur login
$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>

<?php include '../partials/header.php'; ?>

<body class="bg-gradient-to-br from-indigo-600 to-purple-700 min-h-screen flex items-center justify-center py-12">

    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">

        <!-- Logo Wallet Green -->
        <div class="text-center mb-8">
            <div class="mx-auto w-16 h-16 flex items-center justify-center rounded-xl bg-green-100 mb-4">
                <i class="fas fa-wallet text-3xl text-green-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Connexion</h1>
            <p class="text-gray-600 mt-2">Accédez à votre wallet</p>
        </div>


        <!-- Message d'erreur -->
        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire Login -->
        <form method="POST" action="../controllers/AuthController.php?action=login" class="space-y-4">
            <!--csrf tokken -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Email
                </label>
                <input
                    type="email"
                    name="email"
                    required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                    placeholder="email@example.com">
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Mot de passe
                </label>
                <input
                    type="password"
                    name="password"
                    required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                    placeholder="••••••••">
            </div>

            <!-- Submit -->
            <button
                type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded-lg font-semibold hover:bg-indigo-700 transition">
                Se connecter
            </button>
        </form>

        <!-- Footer -->
        <p class="text-center text-sm text-gray-500 mt-6">
            Pas encore de compte ?
            <a href="register.php" class="text-indigo-600 font-medium hover:underline">
                Créer un compte
            </a>
        </p>

    </div>

</body>

</html>