<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Core\App;

class SecurityService {
    private UserRepository $userRepository;
    private static ?SecurityService $instance = null;

    public function __construct() {
        $this->userRepository = App::getDependency('UserRepository');
    }

    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function register(array $userData): bool {
        try {
            // Ici on pourrait ajouter la logique de création du compte principal
            $created = $this->userRepository->create($userData);
            
            if ($created) {
                // Créer automatiquement un compte principal pour l'utilisateur
                $compteService = App::getDependency('CompteService');
                $compteData = [
                    'numero' => 'CPT-' . uniqid(),
                    'numerotel' => $userData['login'],
                    'solde' => 0,
                    'typecompte' => 'principal',
                    'userid' => $this->userRepository->getLastInsertId()
                ];
                
                return $compteService->ajouterPrincipal($compteData);
            }
            
            return false;
        } catch(\Exception $e) {
            throw new \Exception("Erreur lors de l'inscription: " . $e->getMessage());
        }
    }

    public function login(string $login, string $password): array|false {
        try {
            error_log("Tentative de connexion pour login: $login");
            
            // Mode de démo sans base de données
            if ($login === '771234567' && $password === 'test123') {
                error_log("Mode démo - connexion réussie");
                return [
                    'id' => 1,
                    'nom' => 'Demo',
                    'prenom' => 'User',
                    'login' => '771234567',
                    'typeuser' => 'client'
                ];
            }
            
            $user = $this->userRepository->Selectloginandpassword($login, $password);
            error_log("Résultat de la requête: " . print_r($user, true));
            
            if ($user) {
                // Enlever le mot de passe avant de stocker dans la session
                unset($user['password']);
                error_log("Utilisateur connecté avec succès: " . $user['login']);
                return $user;
            }
            error_log("Aucun utilisateur trouvé avec ces identifiants");
            return false;
        } catch(\Exception $e) {
            error_log("Exception dans SecurityService::login: " . $e->getMessage());
            // En cas d'erreur DB, mode démo
            if ($login === '771234567' && $password === 'test123') {
                error_log("Mode démo activé à cause de l'erreur DB");
                return [
                    'id' => 1,
                    'nom' => 'Demo',
                    'prenom' => 'User',
                    'login' => '771234567',
                    'typeuser' => 'client'
                ];
            }
            throw new \Exception("Erreur lors de la connexion: " . $e->getMessage());
        }
    }
}

