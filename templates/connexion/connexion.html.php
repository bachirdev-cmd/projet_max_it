<?php
use App\Core\Session;

$session = Session::getInstance();
$errors = $session->get('errors') ?? [];
$session->unset(key:'errors');
?>


<div class="flex w-full max-w-6xl mx-auto">
        <!-- Formulaire de connexion -->

        <div class="flex-1 flex items-center justify-center p-8">
            <div class="bg-white rounded-3xl shadow-2xl p-8 w-full max-w-md">
                <!-- Logo/Titre -->
                <div class="text-center mb-8">
                    <div class="inline-block bg-gray-100 rounded-2xl px-6 py-3">
                        <h1 class="text-xl font-bold text-gray-800">MAX IT</h1>
                        <p class="text-orange-500 text-sm font-medium">SA</p>
                    </div>
                </div>

                <!-- Formulaire -->
<form class="space-y-6" action="/authentification" method="post">
    
    <!-- Erreur globale -->
        <div class="mb-4 text-red-600 font-semibold text-center">
        </div>
      

    <div>
        <label class="block text-gray-700 text-sm font-medium mb-2">
            Login
        </label>
        <input type="tel" name="login" 
               
               class="w-full px-4 py-3 rounded-xl border focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" 
               placeholder="">

            <p class="mt-1 text-red-600 text-sm"></p>
             
<?php if (!empty($errors['login'])): ?>
            <p class="mt-1 text-red-600 text-sm"><?= htmlspecialchars($errors['login']) ?></p>
        <?php endif; ?>
    </div>

    <div>

        <label class="block text-gray-700 text-sm font-medium mb-2">
            Mot de passe*
        </label>
        <input type="password" name="password" 
               class="w-full px-4 py-3 rounded-xl border focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent" 
               placeholder="">

            <p class="mt-1 text-red-600 text-sm"></p>
        <?php if (!empty($errors['password'])): ?>
            <p class="mt-1 text-red-600 text-sm"><?= htmlspecialchars($errors['password']) ?></p>
        <?php endif; ?>
    </div>

    <div class="text-left">
        <a href="#" class="text-gray-600 text-sm hover:text-orange-500 transition-colors">
            Mot de passe oublié?
        </a>
    </div>

    <button type="submit"  class="w-full bg-orange-500 text-white py-3 rounded-xl font-semibold hover:bg-orange-600 transition-colors">
        Connexion
    </button>

    <div class="text-center text-sm text-gray-600">
        vous n'avez pas de compte ? 
        <a href="/inscription" class="text-orange-500 hover:text-orange-600 transition-colors">s'inscrire</a>
    </div>
</form>

            </div>
        </div>
    </div>

