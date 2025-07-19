<?php
use App\Core\Session;
$session = Session::getInstance();
$errors = $session->get('errors') ?? [];
$session->unset('errors');
?>
<main class="flex items-center justify-center min-h-[calc(100vh-80px)] p-4">
    <div class="w-full max-w-2xl">
        <div class="border border-gray-600 rounded-lg p-8 bg-gray-900">
            <?php if (!empty($error)): ?>
                <div class="bg-red-500 text-white px-4 py-3 rounded mb-6 text-center font-bold">
                    <?= $error ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="/ajout">
                <!-- Phone Number Input -->
                <div class="mb-8">
                    <label class="block text-white text-lg mb-4">
                        Entrer votre numero de telephone 
                        <span class="text-red-500">(*)</span>
                    </label>
                    <input 
                        type="tel" 
                        name="numerotel"
                        placeholder="Entrer le numero..." 
                        class="w-full p-4 bg-gray-800 border border-gray-600 rounded text-white placeholder-gray-400 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500"
                        required
                    >
                    <?php if (!empty($errors['numero_tel'])): ?>
                        <div class="text-red-500 mt-2"><?= htmlspecialchars($errors['numero_tel']) ?></div>
                    <?php endif; ?>
                </div>
                
                <!-- Balance Input -->
                <div class="mb-8">
                    <label class="block text-white text-lg mb-4">
                        Mettre un solde
                    </label>
                    <input 
                        type="number" 
                        name="solde"
                        placeholder="Entrer le solde" 
                        min="0"
                        step="0.01"
                        class="w-full p-4 bg-gray-800 border border-gray-600 rounded text-white placeholder-gray-400 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500"
                        required
                    >
                    <?php if (!empty($errors['solde'])): ?>
                        <div class="text-red-500 mt-2"><?= htmlspecialchars($errors['solde']) ?></div>
                    <?php endif; ?>
                </div>
                
                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-orange-500 text-black px-8 py-3 rounded-lg font-semibold hover:bg-orange-600 transition-colors flex items-center space-x-2">
                        <span>Enregistrer</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>
