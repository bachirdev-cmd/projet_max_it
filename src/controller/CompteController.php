<?php
namespace App\Controller;

use App\Service\CompteService;
use App\Service\TransactionService;
use App\Core\Abstract\AbstractController;
use App\Core\Session;
use App\Core\App;
use App\Core\Validator;

class CompteController extends AbstractController
{
    public Session $session;
    private CompteService $compteService;
    private TransactionService $transactionService;
    private Validator $validator;

    public function __construct(){
        $this->compteService = App::getDependency('CompteService');
        $this->transactionService = App::getDependency('TransactionService');
        $this->session = App::getDependency('Session');
        $this->validator = App::getDependency('Validator');
    }

    public function index() {
            try {
            $user = $this->session->get('user');
            error_log("User dans la session: " . print_r($user, true));

            if (!$user || !isset($user['id'])) {
                error_log("Utilisateur non connecté ou ID manquant");
                header('Location: /');
                exit();
            }

            $compte = $this->compteService->getSolde($user['id']);
            error_log("Compte récupéré: " . print_r($compte, true));

            if (!$compte) {
                error_log("Aucun compte trouvé pour l'utilisateur " . $user['id']);
                // Au lieu de rediriger, on va créer un compte principal automatiquement
                $compteService = App::getDependency('CompteService');
                $compteData = [
                    'numero' => 'CPT-' . uniqid(),
                    'numerotel' => $user['login'] ?? $user['telephone'] ?? '',
                    'solde' => 0,
                    'typecompte' => 'principal',
                    'userid' => $user['id']
                ];
                
                if ($compteService->ajouterPrincipal($compteData)) {
                    // Récupérer le compte nouvellement créé
                    $compte = $compteService->getSolde($user['id']);
                } else {
                    // Si on ne peut toujours pas créer le compte, rediriger avec erreur
                    $this->validator->addError('general', "Impossible de créer le compte principal");
                    header('Location: /erreur');
                    exit();
                }
            }

            // Récupérer les transactions
            $transactions = $this->transactionService->getLast10TransactionsByUserId($user['id']);
            error_log("Transactions récupérées: " . print_r($transactions, true));

            // Définir le layout et rendre la vue
            $this->communLayout = 'baseLayout';
            $this->renderIndex('comptes/accueil', [
                'compte' => $compte,
                'transactions' => $transactions,
                'user' => $user
            ]);

        } catch (\Exception $e) {
            error_log("Erreur dans CompteController::index: " . $e->getMessage());
            header('Location: /erreur');
            exit();
        }
    }

    public function show(){}
    public function create(){}
    public function store(){}
    public function edit(){}
    public function update(){}
    public function delete(){}

    public function storeSecondaire() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = Validator::getInstance();
            $user = $this->session->get('user');
            $data = [
                'numero' => uniqid('CPT-'),
                'datecreation' => date('Y-m-d H:i:s'),
                'solde' => isset($_POST['solde']) ? floatval($_POST['solde']) : 0,
                'numerotel' => $_POST['numerotel'] ?? '',
                'userid' => $user['id']
            ];

            // Validation simple
            $errors = [];
            if (empty($data['numerotel'])) {
                $errors['numero_tel'] = "Le numéro de téléphone est obligatoire.";
            }
            if (!preg_match('/^\d{9,15}$/', $data['numerotel'])) {
                $errors['numero_tel'] = "Le numéro de téléphone est invalide.";
            }
            if ($data['solde'] === '' || !is_numeric($data['solde']) || $data['solde'] < 0) {
                $errors['solde'] = "Le solde doit être un nombre positif.";
            }

            // Récupérer le compte principal
            $comptePrincipal = $this->compteService->getSolde($user['id']);
            $principalSolde = isset($comptePrincipal['solde']) ? floatval($comptePrincipal['solde']) : 0;
            if (!$comptePrincipal) {
                $errors['solde'] = "Aucun compte principal trouvé.";
            } elseif ($principalSolde < $data['solde']) {
                $errors['solde'] = "Solde indisponible";
            }

            if (!empty($errors)) {
                foreach ($errors as $field => $msg) {
                    $validator->addError($field, $msg);
                }
                $this->renderIndex('createaccount/createaccount', [
                    'error' => implode('<br>', $errors)
                ]);
                return;
            }

            $result = $this->compteService->ajouterSecondaire($data);

            if ($result) {
                // Débiter le compte principal
                $nouveauSolde = $principalSolde - $data['solde'];
                $this->compteService->updateSolde($comptePrincipal['id'], $nouveauSolde);

                // Rafraîchir la session du compte principal pour afficher le bon solde sur /accueil
                $compteMaj = $this->compteService->getSolde($user['id']);
                $this->session->set('compte', $compteMaj);

                header('Location: /accueil');
                exit();
            } else {
                $error = "Erreur lors de l'ajout du compte secondaire.";
                $this->renderIndex('createaccount/createaccount', ['error' => $error]);
            }
        } else {
            $this->renderIndex('createaccount/createaccount');
        }
    }



}
