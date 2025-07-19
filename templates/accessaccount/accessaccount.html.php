<div class="max-w-md mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <span class="bg-orange-500 text-white px-4 py-2 rounded-md text-sm font-medium">
                Liste des comptes secondaires
            </span>
        </div>

        <!-- Liste des comptes secondaires -->
        <div class="space-y-4">
            <?php if (!empty($comptesSecondaires)): ?>
                <?php foreach ($comptesSecondaires as $compte): ?>
                    <div class="bg-gray-200 rounded-lg p-4 flex items-center justify-between">
                        <span class="text-black font-medium">Tel : <?= htmlspecialchars($compte['numerotel']) ?></span>
                        <button class="bg-black text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-800 transition-colors">
                            Se connecter
                        </button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-gray-500 text-center">Aucun compte secondaire trouvé.</div>
            <?php endif; ?>
        </div>
    </div>