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

    public function login(string $login, string $password): array|false {
        try {
            return $this->userRepository->Selectloginandpassword($login, $password);
        } catch(\Exception $e) {
            throw new \Exception("Erreur lors de la connexion: " . $e->getMessage(), 0, $e);
        }
    }
}

