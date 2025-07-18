<?php
$compte = $this->session->get('compte');
$user = $this->session->get('user');
$transactions = $this->session->get('transactions') ?? [];
?>
        <!-- Balance Card Container -->
        <div class="border border-gray-600 rounded-3xl p-8 mb-8 bg-gray-900/50">
            <!-- Solde compte badge -->
            <div class="mb-6">
                <span class="bg-orange-500 text-white px-6 py-3 rounded-2xl font-semibold text-sm">
                    Solde compte
                </span>
            </div>
            
            <!-- Main Balance Card -->
            <?php if($compte): ?>

            <div class="bg-gradient-to-r from-orange-500 via-orange-500 to-orange-600 rounded-3xl p-8 flex items-center justify-between">
                <!-- Left Side - Balance Info -->
                <div class="flex items-center space-x-6">
                    <div class="w-20 h-20 bg-black rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M2 6H22V4H2V6ZM21 8H3C2.45 8 2 8.45 2 9V19C2 19.55 2.45 20 3 20H21C21.55 20 22 19.55 22 19V9C22 8.45 21.55 8 21 8ZM20 18H4V10H20V18ZM6 12H8V16H6V12ZM18 12H10V14H18V12ZM18 15H10V16H18V15Z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-white text-3xl font-bold mb-2">Total Balance</div>
                        <div class="text-white text-4xl font-bold flex items-center">

                            <span id="solde-value">
                                <?= number_format($compte['solde'],0,',','') ?>
                            </span>
                            <span class="text-4xl ml-2">Frcs</span>
                            <svg id="toggle-solde" class="w-7 h-7 ml-3 opacity-80 cursor-pointer" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                            </svg>
                        </div>
                        <!-- <script>
                            const toggleSolde = document.getElementById('toggle-solde');
                            const soldeValue = document.getElementById('solde-value');
                            let hidden = false;
                            toggleSolde.addEventListener('click', function() {
                                hidden = !hidden;
                                
                            });
                        </script> -->
                    </div>
                </div>
                
                <!-- Right Side - Account Number -->
                <div class="bg-black rounded-2xl px-8 py-6 text-center">
                    <div class="text-white text-xl font-semibold mb-4">Number of account</div>
                    <div class="bg-white text-black rounded-xl px-6 py-3 inline-block">
                        <span class="text-3xl font-bold">
                            0
                        </span>
                    </div>
                </div>
            </div>
        </div>
    <?php endif?>

        <!-- Transactions Section -->
        <div class="border border-gray-600 rounded-3xl p-8 bg-gray-900/50">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <span class="bg-orange-500 text-white px-6 py-3 rounded-2xl font-semibold text-sm">
                    Voir transactions
                </span>
                <a href="/voirplus" class="bg-orange-500 text-white px-6 py-3 rounded-2xl font-semibold text-sm flex items-center space-x-3 hover:bg-orange-600 transition">
                    <span>Voir Plus</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                </a>
            </div>
            
            <!-- Transaction List -->
            <div class="space-y-4">
                <?php if (!empty($transactions)): ?>
                    <?php foreach ($transactions as $transaction): ?>
                        <div class="flex items-center justify-between p-5 bg-gray-800 rounded-2xl hover:bg-gray-700 transition">
                            <div class="flex items-center space-x-5">
                                <div class="w-12 h-12 
                                    <?= $transaction['typetransaction'] === 'depot' ? 'bg-green-500' : ($transaction['typetransaction'] === 'retrait' ? 'bg-red-500' : 'bg-blue-500') ?> 
                                    rounded-full flex items-center justify-center">
                                    <!-- Icone selon type -->
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
                <div class="text-gray-400 text-center">Aucune transaction récente.</div>
            <?php endif; ?>
        </div>
    </div>
 </div>
