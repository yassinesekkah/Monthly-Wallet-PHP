<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once '../../config/database.php';

use App\Service\SecurityService;

$csrfToken = SecurityService::generateCSRFToken();

// Récupérer les messages d'erreur
$error = $_SESSION['register_error'] ?? '';
unset($_SESSION['register_error']);
?>

<?php include '../partials/header.php'; ?>

<body class="bg-gradient-to-br from-indigo-600 to-purple-700 min-h-screen flex items-center justify-center py-12">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
        <!-- Logo Card -->
        <div class="text-center mb-8">
            <div class="mx-auto w-16 h-16 flex items-center justify-center rounded-xl bg-indigo-100 mb-4">
                <i class="fas fa-credit-card text-3xl text-indigo-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Créer un compte</h1>
            <p class="text-gray-600 mt-2">Votre wallet personnel en ligne</p>
        </div>

        <!-- Message d'erreur -->
        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <i class="fas fa-exclamation-circle mr-2"></i><?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire -->

        <body class="min-h-screen bg-gray-100 flex items-center justify-center">
            <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">

                <!-- Form -->
                <form method="POST" action="../controllers/AuthController.php?action=register" class="space-y-2">
                    <!-- csrf tokken -->
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nom complet
                        </label>
                        <input
                            type="text"
                            name="name"
                            required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                            placeholder="Votre nom">
                    </div>

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
                        <p class="text-xs text-gray-400 mt-1">
                            Minimum 8 caractères, avec majuscule et minuscule
                        </p>
                    </div>

                    <!-- Submit -->
                    <button
                        type="submit"
                        class="w-full bg-indigo-600 text-white py-2 rounded-lg font-semibold hover:bg-indigo-700 transition">
                        S'inscrire
                    </button>
                </form>

                <!-- Footer -->
                <p class="text-center text-sm text-gray-500 mt-6">
                    Déjà un compte ?
                    <a href="login.php" class="text-indigo-600 font-medium hover:underline">
                        Se connecter
                    </a>
                </p>
            </div>
        </body>

        </html>