<?php
namespace App\Service;

use App\Repository\CompteRepository;
use App\Core\App;

class CompteService {
    private CompteRepository $compteRepository;
    private static ?CompteService $instance = null;

    public function __construct() {
        $this->compteRepository = App::getDependency('CompteRepository');
    }

    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    } 

    public function getSolde(int $userId): ?array {
        try {
            error_log("Recherche du solde pour l'utilisateur: " . $userId);
            $compte = $this->compteRepository->getSoldeByUserId($userId);
            error_log("Compte trouvé: " . print_r($compte, true));
            return $compte;
        } catch (\Exception $e) {
            error_log("Erreur lors de la récupération du solde: " . $e->getMessage());
            return null;
        }
    }

    public function ajouterSecondaire(array $data): bool {
        return $this->compteRepository->ajouterSecondaire($data);
    }

    public function updateSolde(int $compteId, float $nouveauSolde): void {
        $this->compteRepository->updateSolde($compteId, $nouveauSolde);
    }

    public function ajouterPrincipal(array $data): bool {
        try {
            // Génération du numéro de compte principal
            if (empty($data['numero'])) {
                $data['numero'] = 'CPT-' . uniqid();
            }
            
            // Ajout de la date de création si non fournie
            if (empty($data['datecreation'])) {
                $data['datecreation'] = date('Y-m-d H:i:s');
            }

            // Assurer que c'est un compte principal
            $data['typecompte'] = 'principal';
            
            return $this->compteRepository->create($data);
        } catch(\Exception $e) {
            // Log l'erreur
            error_log("Erreur création compte principal: " . $e->getMessage());
            return false;
        }
    }
}