<header class="flex items-center justify-between px-6 py-4 bg-black">
        <!-- Logo -->
        <div class="flex items-center space-x-3">
            <div class="bg-orange-500 px-2 py-1 rounded text-white font-bold text-xs">
                Max it
            </div>
            <span class="text-white font-bold text-2xl tracking-wide">MAX IT</span>
        </div>
        
        <!-- Navigation Buttons -->
        <div class="flex items-center space-x-3">
            <a href="/accueil" class="border border-yellow-500 text-yellow-500 px-5 py-2 rounded-full text-sm font-medium hover:bg-yellow-500 hover:text-black transition">
                Dashboard
            </a>
            <a href="/createaccount" class="border border-yellow-500 text-yellow-500 px-5 py-2 rounded-full text-sm font-medium hover:bg-yellow-500 hover:text-black transition">
                Create account
            </a>
            <a href="/accessaccount" class="border border-yellow-500 text-yellow-500 px-5 py-2 rounded-full text-sm font-medium hover:bg-yellow-500 hover:text-black transition">
                Access account
            </a>
            <a href="/logout" class="bg-orange-500 text-white px-5 py-2 rounded-full text-sm font-medium hover:bg-orange-600 transition text-center">
                Déconnexion
            </a>
        </div>
        
        <!-- User Profile -->
        <div class="flex items-center space-x-3">
            <div class="text-right">
                <div class="text-white font-semibold text-base">
                    <div><?= isset($user['prenom']) ? htmlspecialchars($user['prenom']) : '' ?></div>
                    <div><?= isset($user['nom']) ? htmlspecialchars($user['nom']) : '' ?>
                </div>
                    </div>
                <div class="text-gray-400 text-sm">
                    <?= isset($activeCompte['numero_tel']) ? htmlspecialchars($activeCompte['numero_tel']) : (isset($user['numero_tel']) ? htmlspecialchars($user['numero_tel']) : '') ?>
                </div>
            </div>
            <div class="w-12 h-12 rounded-full overflow-hidden">
                <img 
                    src="<?php
                        if (!empty($user['photo'])) {
                            echo '/' . htmlspecialchars($user['photo']);
                        } else {
                            echo 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDgiIGhlaWdodD0iNDgiIHZpZXdCb3g9IjAgMCA0OCA0OCIgZmlsbD0ibm9uZSIgeG1zbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjQiIGN5PSIyNCIgcj0iMjQiIGZpbGw9InVybCgjZ3JhZGllbnQwX2xpbmVhcl8xXzEpIi8+CjxwYXRoIGQ9Ik0yNCAyNEM3MC4yOTAzIDI0IDI0IDI4LjUgMjQgMzRWMzZIMjRWMzRDMjQgMjguNSAxNy43MDk3IDI0IDI0IDI0WiIgZmlsbD0id2hpdGUiLz4KPGNpcmNsZSBjeD0iMjQiIGN5PSIxOCIgcj0iNiIgZmlsbD0id2hpdGUiLz4KPGRlZnM+CjxsaW5lYXJHcmFkaWVudCBpZD0iZ3JhZGllbnQwX2xpbmVhcl8xXzEiIHgxPSIwIiB5MT0iMCIgeDI9IjQ4IiB5Mj0iNDgiIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIj4KPHN0b3Agc3RvcC1jb2xvcj0iI0ZGQTc1MyIvPgo8c3RvcCBvZmZzZXQ9IjEiIHN0b3AtY29sb3I9IiNGRjZCMzUiLz4KPC9saW5lYXJHcmFkaWVudD4KPC9kZWZzPgo8L3N2Zz4K';
                        }
                    ?>"
                    alt="Profile"
                    class="w-full h-full object-cover"
                >
            </div>
        </div>
    </header>