<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAX IT - Connexion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body, html {
            width: 100vw;
            height: 100vh;
            margin: 0;
            padding: 0;
        }
        .container-absolute {
            position: absolute;
            left: 0;
            top: 0;
            width: 1800px;
            height: 900px;
        }
        .image-absolute {
            position: absolute;
            left: 0;
            top: 0;
            width: 900px;
            height: 900px;
            object-fit: contain;
            border-radius: 20px;
        }
        .form-absolute {
            position: absolute;
            right: 0;
            top: 120px;
            width: 600px;
            height: 650px;
            background-color: #f97316;
            border-radius: 20px;
            border: 4px solid #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 48px;
            box-sizing: border-box;
        }
        .form-inner {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
    </style>
</head>
<body style="background-color: #f97316;">
    <div class="container-absolute">
        <div class="w-[1050px] flex absolute mr-18 items-center justify-center ml-8 h-[900px]">
            <img 
                src="images/imageconnexion.jpeg" 
                class="image-relative w-full h-full object-contain rounded-xl"
                alt="Image de connexion"
            >
        </div>
        <div class="form-absolute">
            <div class="form-inner">
                <?php 
                use App\Core\Session;
                $session = Session::getInstance();
                $errors = $session->get('errors') ?? [];
                $oldData = $session->get('old_data') ?? [];
                $session->unset('errors');
                ?>

                <?php if (isset($errors['general'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?= htmlspecialchars($errors['general']) ?>
                    </div>
                <?php endif; ?>

                <div class="text-center mb-10">
                    <h2 class="text-4xl font-bold text-white">
                        MAX <span class="text-black">IT</span>
                    </h2>
                </div>

                <form method="POST" action="/authentification" class="space-y-8">
                    <!-- Téléphone -->
                    <div>
                        <label class="block text-lg font-bold text-white mb-2">
                            Entrer votre numéro de téléphone
                        </label>
                        <div class="relative">
                            <input type="tel" 
                                   name="login"
                                   value="<?= htmlspecialchars($oldData['login'] ?? '') ?>"
                                   placeholder="771234567"
                                   maxlength="9"
                                   class="w-full px-6 py-3 bg-orange-200 rounded-lg border-0 focus:outline-none text-black placeholder-black text-lg font-normal <?= isset($errors['login']) ? 'ring-2 ring-red-500' : '' ?>"
                                   required>
                            <i class="fas fa-phone absolute right-4 top-1/2 -translate-y-1/2 text-black text-lg"></i>
                        </div>
                        <?php if (isset($errors['login'])): ?>
                            <p class="text-red-200 text-sm mt-1 font-medium"><?= htmlspecialchars($errors['login']) ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Mot de passe -->
                    <div>
                        <label class="block text-lg font-bold text-white mb-2">
                            Entrer votre mot de passe
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   name="password"
                                   placeholder="Mot de passe"
                                   class="w-full px-6 py-3 bg-orange-200 rounded-lg border-0 focus:outline-none text-black placeholder-black text-lg font-normal <?= isset($errors['password']) ? 'ring-2 ring-red-500' : '' ?>"
                                   required>
                            <i class="fas fa-lock absolute right-4 top-1/2 -translate-y-1/2 text-black text-lg"></i>
                        </div>
                        <?php if (isset($errors['password'])): ?>
                            <p class="text-red-200 text-sm mt-1 font-medium"><?= htmlspecialchars($errors['password']) ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Mot de passe oublié -->
                    <div class="text-right">
                        <a href="#" class="text-white hover:text-orange-200 text-base transition-colors font-normal">
                            mot de passe oublié?
                        </a>
                    </div>

                    <!-- Buttons -->
                    <div class="space-y-4 pt-2">
                        <button type="submit" 
                            class="w-full bg-[#4B2E19] text-white py-3 rounded-2xl font-bold text-lg hover:bg-[#3a2414] transition-colors">
                            Connexion
                        </button>
                        <a href="/inscription" 
                           class="w-full bg-[#A86F3C] text-white py-3 rounded-2xl font-bold text-lg hover:bg-[#8a5a2e] transition-colors block text-center">
                            S'inscrire
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

