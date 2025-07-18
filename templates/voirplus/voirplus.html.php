<?php
$transactions = $transactions ?? [];
?>
<div class="border-2 border-orange-500 rounded-3xl p-6 bg-gray-900/70 max-w-5xl mx-auto mt-8 shadow-lg">
    <!-- Bouton Retour -->
    <div class="mb-6">
        <a href="/accueil" class="bg-orange-500 text-white px-6 py-2 rounded-md font-bold hover:bg-orange-600 transition">
            Retour
        </a>
    </div>
    <!-- Liste des transactions -->
    <div class="border border-gray-600 rounded-3xl p-8 bg-gray-900/50">
        <div class="space-y-4">
            <?php if (!empty($transactions)): ?>
                <?php foreach ($transactions as $transaction): ?>
                    <div class="flex items-center justify-between p-5 bg-gray-800 rounded-2xl hover:bg-gray-700 transition">
                        <div class="flex items-center space-x-5">
                            <div class="w-12 h-12 
                                <?= $transaction['typetransaction'] === 'depot' ? 'bg-green-500' : ($transaction['typetransaction'] === 'retrait' ? 'bg-red-500' : 'bg-blue-500') ?> 
                                rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <?php if ($transaction['typetransaction'] === 'depot'): ?>
                                        <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                                    <?php elseif ($transaction['typetransaction'] === 'retrait'): ?>
                                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                    <?php else: ?>
                                        <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                                    <?php endif; ?>
                                </svg>
                            </div>
                            <div>
                                <div class="text-white font-semibold text-lg">
                                    <?= ucfirst($transaction['typetransaction']) ?> - <?= number_format($transaction['montant'], 0, ',', ' ') ?> Frcs
                                </div>
                                <div class="text-gray-400 text-sm">
                                    <?= date('d/m/Y H:i', strtotime($transaction['date'])) ?>
                                </div>
                            </div>
                        </div>
                        <div class="text-white font-bold text-xl">
                            <?= $transaction['typetransaction'] === 'depot' ? '+' : '-' ?>
                            <?= number_format($transaction['montant'], 0, ',', ' ') ?> Frcs
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-gray-400 text-center">Aucune transaction trouvée.</div>
            <?php endif; ?>
        </div>
    </div>
</div>