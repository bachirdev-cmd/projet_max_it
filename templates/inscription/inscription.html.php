<?php 
    use App\Core\Session;
    use App\Core\Helper;
    
    $session = Session::getInstance();
    $errors = $session->get('errors') ?? [];
    $oldData = $session->get('old_data') ?? [];
    $successMessage = $session->get('success');
    $cniData = $session->get('cni_data');
    
    // Nettoyer les données de session après affichage
    $session->unset('cni_data');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAX IT - Inscription</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex">
    <!-- Left side - Orange with logo -->
    <div class="w-1/2 bg-orange-500 flex flex-col items-center justify-center text-white">
        <div class="text-center">
            <h1 class="text-8xl font-bold mb-4">Max<span class="text-black">it</span></h1>
            <p class="text-4xl font-bold text-black mt-8">SN</p>
        </div>
    </div>

    <!-- Right side - Form -->
    <div class="w-1/2 bg-gray-100 flex items-center justify-center p-8 overflow-y-auto min-h-screen">
        <div class="w-full max-w-md">
            <!-- Form Header -->
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold"><span class="text-orange-500">MAX</span> <span class="text-black">IT</span></h2>
                <p class="text-gray-600 mt-2">Inscription</p>
            </div>

            <?php if ($successMessage): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?= Helper::clean($successMessage) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($errors['general'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?= Helper::clean($errors['general']) ?>
                </div>
            <?php endif; ?>

            <!-- Zone d'erreur dynamique (remplace les alerts JS) -->
            <div id="error-message" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 hidden">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <span id="error-text"></span>
                </div>
            </div>

            <!-- Zone de succès dynamique -->
            <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 hidden">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span id="success-text"></span>
                </div>
            </div>

            <!-- ÉTAPE 1: Formulaire initial (CNI + téléphone + adresse) -->
            <div id="step1" <?= $cniData ? 'style="display:none;"' : '' ?>>
                <form id="cni-form" class="space-y-6">
                    <!-- CNI -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Numéro de carte d'identité (13 chiffres)
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="cni" 
                                   name="cni" 
                                   value="<?= Helper::clean($oldData['cni'] ?? '') ?>"
                                   placeholder="199720000166 (12-13 chiffres)" 
                                   maxlength="13" 
                                   pattern="[0-9]{12,13}"
                                   class="w-full px-4 py-3 bg-gray-300 rounded-lg border-0 focus:outline-none focus:ring-2 focus:ring-orange-500 text-gray-700 placeholder-gray-600 <?= isset($errors['cni']) ? 'ring-2 ring-red-500' : '' ?>">
                            <i class="fas fa-id-card absolute right-3 top-3 text-gray-600"></i>
                        </div>
                        <?php if (isset($errors['cni'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?= Helper::clean($errors['cni']) ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Téléphone -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Numéro de téléphone (9 chiffres)
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="login" 
                                   name="login" 
                                   value="<?= Helper::clean($oldData['login'] ?? '') ?>"
                                   placeholder="771234567" 
                                   maxlength="9" 
                                   pattern="(77|78|70|76|75)[0-9]{7}"
                                   class="w-full px-4 py-3 bg-gray-300 rounded-lg border-0 focus:outline-none focus:ring-2 focus:ring-orange-500 text-gray-700 placeholder-gray-600 <?= isset($errors['login']) ? 'ring-2 ring-red-500' : '' ?>">
                            <i class="fas fa-phone absolute right-3 top-3 text-gray-600"></i>
                        </div>
                        <?php if (isset($errors['login'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?= Helper::clean($errors['login']) ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Adresse -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                        <div class="relative">
                            <input type="text" 
                                   id="adresse" 
                                   name="adresse" 
                                   value="<?= Helper::clean($oldData['adresse'] ?? '') ?>"
                                   placeholder="Adresse complète"
                                   class="w-full px-4 py-3 bg-gray-300 rounded-lg border-0 focus:outline-none focus:ring-2 focus:ring-orange-500 text-gray-700 placeholder-gray-600 <?= isset($errors['adresse']) ? 'ring-2 ring-red-500' : '' ?>">
                            <i class="fas fa-home absolute right-3 top-3 text-gray-600"></i>
                        </div>
                        <?php if (isset($errors['adresse'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?= Helper::clean($errors['adresse']) ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Bouton de vérification CNI -->
                    <button type="button" 
                            id="verify-cni-btn"
                            class="w-full bg-orange-500 text-white py-3 rounded-lg font-medium hover:bg-orange-600 disabled:bg-gray-400 disabled:cursor-not-allowed">
                        <span id="verify-text">Vérifier la CNI</span>
                        <i id="loading-icon" class="fas fa-spinner fa-spin ml-2 hidden"></i>
                    </button>
                </form>
            </div>

            <!-- ÉTAPE 2: Affichage des données CNI + formulaire complet -->
            <div id="step2" <?= !$cniData ? 'style="display:none;"' : '' ?>>
                <?php if ($cniData): ?>
                    <!-- Affichage des données CNI -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-green-800 mb-4">✓ Carte d'identité vérifiée</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">Nom:</span>
                                <span class="text-gray-900"><?= Helper::clean($cniData['nom']) ?></span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Prénom:</span>
                                <span class="text-gray-900"><?= Helper::clean($cniData['prenom']) ?></span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Date de naissance:</span>
                                <span class="text-gray-900"><?= Helper::clean($cniData['date_naissance']) ?></span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Lieu de naissance:</span>
                                <span class="text-gray-900"><?= Helper::clean($cniData['lieu_naissance']) ?></span>
                            </div>
                        </div>
                        
                        <!-- Images CNI -->
                        <div class="flex justify-between mt-4 space-x-4">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-700 mb-2">Recto</p>
                                <img src="<?= Helper::clean($cniData['cni_recto_url']) ?>" alt="CNI Recto" class="w-full h-32 object-cover rounded border">
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-700 mb-2">Verso</p>
                                <img src="<?= Helper::clean($cniData['cni_verso_url']) ?>" alt="CNI Verso" class="w-full h-32 object-cover rounded border">
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Formulaire final d'inscription -->
                <form method="POST" action="/register" enctype="multipart/form-data" class="space-y-6">
                    <input type="hidden" name="csrf_token" value="<?= Helper::generateCsrfToken() ?>">
                    
                    <!-- Champs cachés avec les données CNI -->
                    <?php if ($cniData): ?>
                        <input type="hidden" name="nom" value="<?= Helper::clean($cniData['nom']) ?>">
                        <input type="hidden" name="prenom" value="<?= Helper::clean($cniData['prenom']) ?>">
                        <input type="hidden" name="cni" value="<?= Helper::clean($cniData['cni']) ?>">
                        <input type="hidden" name="date_naissance" value="<?= Helper::clean($cniData['date_naissance']) ?>">
                        <input type="hidden" name="lieu_naissance" value="<?= Helper::clean($cniData['lieu_naissance']) ?>">
                        <input type="hidden" name="cni_recto_url" value="<?= Helper::clean($cniData['cni_recto_url']) ?>">
                        <input type="hidden" name="cni_verso_url" value="<?= Helper::clean($cniData['cni_verso_url']) ?>">
                        <input type="hidden" name="login" value="<?= Helper::clean($oldData['login'] ?? '') ?>">
                        <input type="hidden" name="adresse" value="<?= Helper::clean($oldData['adresse'] ?? '') ?>">
                    <?php endif; ?>

                    <!-- Photo de profil optionnelle -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Photo de profil (optionnelle)
                        </label>
                        <label class="block cursor-pointer">
                            <input type="file" 
                                   name="photo"
                                   accept="image/*"
                                   class="hidden">
                            <div class="border-2 border-gray-400 border-dashed rounded-lg p-8 text-center bg-gray-200 hover:bg-gray-300 transition-colors">
                                <i class="fas fa-camera text-gray-400 text-3xl mb-2"></i>
                                <p class="text-gray-400">Cliquer pour ajouter votre photo</p>
                            </div>
                        </label>
                        <?php if (isset($errors['photo'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?= Helper::clean($errors['photo']) ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Mot de passe -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
                            <div class="relative">
                                <input type="password" 
                                       name="password" 
                                       placeholder="Mot de passe" 
                                       minlength="6"
                                       class="w-full px-4 py-3 bg-gray-300 rounded-lg border-0 focus:outline-none focus:ring-2 focus:ring-orange-500 text-gray-700 placeholder-gray-600 <?= isset($errors['password']) ? 'ring-2 ring-red-500' : '' ?>">
                                <i class="fas fa-lock absolute right-3 top-3 text-gray-600"></i>
                            </div>
                            <?php if (isset($errors['password'])): ?>
                                <p class="text-red-500 text-sm mt-1"><?= Helper::clean($errors['password']) ?></p>
                            <?php endif; ?>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirmer</label>
                            <div class="relative">
                                <input type="password" 
                                       name="password_confirm" 
                                       placeholder="Confirmer" 
                                       minlength="6"
                                       class="w-full px-4 py-3 bg-gray-300 rounded-lg border-0 focus:outline-none focus:ring-2 focus:ring-orange-500 text-gray-700 placeholder-gray-600 <?= isset($errors['password_confirm']) ? 'ring-2 ring-red-500' : '' ?>">
                                <i class="fas fa-lock absolute right-3 top-3 text-gray-600"></i>
                            </div>
                            <?php if (isset($errors['password_confirm'])): ?>
                                <p class="text-red-500 text-sm mt-1"><?= Helper::clean($errors['password_confirm']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Submit button -->
                    <button type="submit" class="w-full bg-orange-500 text-white py-3 rounded-lg font-medium hover:bg-orange-600">
                        Finaliser l'inscription
                    </button>

                    <!-- Bouton pour recommencer -->
                    <button type="button" 
                            id="restart-btn"
                            class="w-full bg-gray-500 text-white py-3 rounded-lg font-medium hover:bg-gray-600">
                        Recommencer avec une autre CNI
                    </button>
                </form>
            </div>

            <p class="text-center text-sm text-gray-600 mt-6">
                Déjà un compte? <a href="/" class="text-orange-500 hover:underline">Connexion</a>
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const verifyCniBtn = document.getElementById('verify-cni-btn');
            const verifyText = document.getElementById('verify-text');
            const loadingIcon = document.getElementById('loading-icon');
            const step1 = document.getElementById('step1');
            const step2 = document.getElementById('step2');
            const restartBtn = document.getElementById('restart-btn');
            
            // Fonctions pour afficher les messages
            function showError(message) {
                const errorDiv = document.getElementById('error-message');
                const errorText = document.getElementById('error-text');
                const successDiv = document.getElementById('success-message');
                
                successDiv.classList.add('hidden');
                errorText.textContent = message;
                errorDiv.classList.remove('hidden');
                
                // Scroll vers le message
                errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            
            function showSuccess(message) {
                const successDiv = document.getElementById('success-message');
                const successText = document.getElementById('success-text');
                const errorDiv = document.getElementById('error-message');
                
                errorDiv.classList.add('hidden');
                successText.textContent = message;
                successDiv.classList.remove('hidden');
                
                // Scroll vers le message
                successDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            
            function hideMessages() {
                document.getElementById('error-message').classList.add('hidden');
                document.getElementById('success-message').classList.add('hidden');
            }

            // Gestion de la vérification CNI
            verifyCniBtn.addEventListener('click', function() {
                hideMessages();
                const cni = document.getElementById('cni').value.trim();
                const login = document.getElementById('login').value.trim();
                const adresse = document.getElementById('adresse').value.trim();

                // Validation simple (accepte 12 ou 13 chiffres)
                if (!cni || !/^\d{12,13}$/.test(cni)) {
                    showError('Veuillez entrer un numéro CNI valide (12 ou 13 chiffres)');
                    return;
                }

                if (!login || login.length !== 9 || !/^(77|78|70|76|75)\d{7}$/.test(login)) {
                    showError('Veuillez entrer un numéro de téléphone valide (9 chiffres: 77xxxxxxx)');
                    return;
                }

                if (!adresse) {
                    showError('Veuillez entrer votre adresse');
                    return;
                }

                // Interface loading
                verifyCniBtn.disabled = true;
                verifyText.textContent = 'Vérification en cours...';
                loadingIcon.classList.remove('hidden');

                // Appel AJAX pour vérifier la CNI
                fetch('/verify-cni', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        cni: cni,
                        login: login,
                        adresse: adresse
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // CNI valide - afficher succès et rediriger
                        showSuccess('CNI vérifiée avec succès ! Redirection...');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        // CNI invalide
                        showError(data.message || 'Le numéro de CNI n\'existe pas');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showError('Une erreur est survenue lors de la vérification');
                })
                .finally(() => {
                    // Restaurer l'interface
                    verifyCniBtn.disabled = false;
                    verifyText.textContent = 'Vérifier la CNI';
                    loadingIcon.classList.add('hidden');
                });
            });

            // Gestion du bouton recommencer
            if (restartBtn) {
                restartBtn.addEventListener('click', function() {
                    // Effacer les données de session et recommencer
                    fetch('/clear-cni-session', {
                        method: 'POST'
                    }).then(() => {
                        window.location.href = '/inscription';
                    });
                });
            }
        });
    </script>
</body>
</html>
