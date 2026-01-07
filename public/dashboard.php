<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once '../config/database.php';

use App\Service\SecurityService;

SecurityService::requireLogin();

$name = $_SESSION['user_name'];

// Récupérer les messages d'erreur
$success = $_SESSION['budget_success'] ?? '';
unset($_SESSION['budget_success']);

$error = $_SESSION['budget_error'] ?? '';
unset($_SESSION['budget_error']);
?>

<?php include "partials/header.php"; ?>
<?php include "partials/navbar.php"; ?>

<!-- Hero Section -->
<section class="max-w-7xl mx-auto px-6 mt-6">
    <div class="bg-white/70 backdrop-blur rounded-3xl shadow-xl p-8 flex flex-col md:flex-row justify-between items-center gap-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Bonjour <?= $name ?> 👋
            </h1>
            <p class="text-gray-600 mt-2">
                Voici un aperçu clair de votre situation financière ce mois-ci.
            </p>
        </div>

        <div class="flex gap-3">
            <a href="wallet.php"
                class="bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 transition">
                Voir mon wallet
            </a>
            <a href="wallet.php#add"
                class="bg-gray-800 text-white px-6 py-3 rounded-xl hover:bg-gray-900 transition">
                Ajouter une dépense
            </a>
        </div>
    </div>
</section>

<!-- Message d'erreur -->
        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <i class="fas fa-exclamation-circle mr-2"></i><?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Message d'erreur -->
        <?php if ($success): ?>
            <div class="bg-green-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <i class="fas fa-exclamation-circle mr-2"></i><?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

<!-- Stats Cards -->
<section class="max-w-7xl mx-auto px-6 mt-10">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Budget -->
        <div class="relative bg-white rounded-2xl shadow-lg p-6 overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-green-100 rounded-full -mr-10 -mt-10"></div>

            <div class="relative flex justify-between items-start">
                <!-- Budget info -->
                <div>
                    <p class="text-sm text-gray-500">Budget mensuel</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">3000 MAD</p>
                    <i class="fas fa-coins text-green-500 text-xl mt-4"></i>
                </div>

                <!-- Modifier button -->
                <button
                    onclick="openBudgetModal()"
                    class="flex items-center gap-1 text-sm font-semibold text-green-600 hover:text-green-700 transition">
                    <i class="fas fa-pen"></i>
                    Modifier
                </button>
            </div>
        </div>


        <!-- Dépenses -->
        <div class="relative bg-white rounded-2xl shadow-lg p-6 overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-red-100 rounded-full -mr-10 -mt-10"></div>
            <div class="relative">
                <p class="text-sm text-gray-500">Total dépensé</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">1750 MAD</p>
                <i class="fas fa-arrow-down text-red-500 text-xl mt-4"></i>
            </div>
        </div>

        <!-- Solde -->
        <div class="relative bg-white rounded-2xl shadow-lg p-6 overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-100 rounded-full -mr-10 -mt-10"></div>
            <div class="relative">
                <p class="text-sm text-gray-500">Solde restant</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">1250 MAD</p>
                <i class="fas fa-wallet text-blue-500 text-xl mt-4"></i>
            </div>
        </div>

    </div>
</section>

<!-- Recent Expenses -->
<section class="max-w-7xl mx-auto px-6 mt-12 mb-16">
    <div class="bg-white rounded-3xl shadow-lg p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">
                Dépenses récentes
            </h2>
            <a href="wallet.php" class="text-green-600 text-sm hover:underline">
                Voir tout
            </a>
        </div>

        <ul class="space-y-4">
            <li class="flex justify-between items-center bg-gray-50 rounded-xl px-5 py-4">
                <span class="text-gray-700">Loyer</span>
                <span class="font-semibold text-gray-800">1500 MAD</span>
            </li>
            <li class="flex justify-between items-center bg-gray-50 rounded-xl px-5 py-4">
                <span class="text-gray-700">Transport</span>
                <span class="font-semibold text-gray-800">120 MAD</span>
            </li>
            <li class="flex justify-between items-center bg-gray-50 rounded-xl px-5 py-4">
                <span class="text-gray-700">Café</span>
                <span class="font-semibold text-gray-800">30 MAD</span>
            </li>
        </ul>
    </div>
</section>

<!-- Budget Modal -->
<div id="budgetModal"
    class="fixed inset-0 bg-black/40 flex items-center justify-center hidden z-50">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative">

        <!-- Close -->
        <button onclick="closeBudgetModal()"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <i class="fas fa-times"></i>
        </button>

        <!-- Title -->
        <h2 class="text-2xl font-bold text-gray-800 mb-2">
            Modifier le budget
        </h2>
        <p class="text-sm text-gray-500 mb-6">
            Définissez votre budget mensuel
        </p>

        <!-- Form -->
        <form method="POST" action="controllers/WalletController.php?action=updateBudget"
            class="space-y-4">

            <!-- CSRF token -->
            <input type="hidden" name="csrf_token"
                value="<?= htmlspecialchars(\App\Service\SecurityService::generateCSRFToken()) ?>">
            <!-- Budget -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Budget (MAD)
                </label>
                <input
                    type="number"
                    name="budget"
                    min="1"
                    step="1"
                    required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none"
                    placeholder="Ex: 3000">
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 pt-4">
                <button type="button"
                    onclick="closeBudgetModal()"
                    class="px-4 py-2 rounded-lg border text-gray-600 hover:bg-gray-100">
                    Annuler
                </button>

                <button type="submit"
                    class="px-5 py-2 rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700 transition">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openBudgetModal() {
        document.getElementById('budgetModal').classList.remove('hidden');
    }

    function closeBudgetModal() {
        document.getElementById('budgetModal').classList.add('hidden');
    }
</script>

</body>

</html>