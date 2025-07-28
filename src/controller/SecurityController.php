<?php
namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Service\SecurityService;
use App\Core\Session;
use App\Core\Validator;
use App\Core\App;
use App\Core\FileUpload;

class SecurityController extends AbstractController 
{
    private SecurityService $securityService;
    private Validator $validator;
    protected Session $session;

    public function __construct()
    {
        $this->securityService = App::getDependency('SecurityService');
        $this->communLayout = 'security.layout';
        $this->session = App::getDependency('Session');
        $this->validator = App::getDependency('Validator');
    }

    public function index(){
        $this->renderIndex('connexion/connexion');
    }
    public function show(){}
    public function create(){}
    public function store(){}
    public function edit(){}
    public function update(){}
    public function delete(){}

    public function login() {
        $loginData = [
            'login' => $_POST['login'] ?? '',
            'password' => $_POST['password'] ?? ''
        ];

        $this->session->unset('errors');
        $this->session->unset('old_data');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("=== DÉBUT TENTATIVE DE CONNEXION ===");
            error_log("Login: " . $loginData['login']);
            error_log("Password length: " . strlen($loginData['password']));
            
            if ($this->validator->validateLogin($loginData)) {
                error_log("✓ Validation réussie");
                try {
                    $user = $this->securityService->login($loginData['login'], $loginData['password']);
                    error_log("SecurityService::login retourné: " . print_r($user, true));
                    
                    if ($user) {
                        error_log("✓ Utilisateur trouvé, mise en session");
                        // Connexion réussie - récupération des données utilisateur
                        $this->session->set('user', $user);
                        
                        error_log("✓ Récupération du compte principal");
                        // Récupération du compte principal
                        $compteService = App::getDependency('CompteService');
                        $compte = $compteService->getSolde($user['id']);
                        error_log("Compte récupéré: " . print_r($compte, true));
                        
                        if ($compte) {
                            $this->session->set('compte', $compte);
                        }
                        
                        error_log("✓ Récupération des transactions");
                        // Récupération des dernières transactions
                        $transactions = App::getDependency('TransactionService')->getLast10TransactionsByUserId($user['id']);
                        $this->session->set('transactions', $transactions);
                        
                        error_log("✓ Redirection vers /accueil");
                        // Redirection vers l'accueil
                        header('Location: /accueil');
                        exit();
                    } else {
                        error_log("✗ SecurityService::login a retourné false");
                        // Identifiants incorrects
                        $this->validator->addError('general', "Identifiants incorrects");
                        $this->session->set('old_data', ['login' => $loginData['login']]);
                        header('Location: /');
                        exit();
                    }
                } catch (\Exception $e) {
                    // Erreur technique
                    error_log("✗ Exception attrapée: " . $e->getMessage());
                    error_log("Stack trace: " . $e->getTraceAsString());
                    $this->validator->addError('general', "Une erreur est survenue lors de la connexion");
                    $this->session->set('old_data', ['login' => $loginData['login']]);
                    header('Location: /');
                    exit();
                }
            } else {
                error_log("✗ Validation échouée");
                error_log("Erreurs de validation: " . print_r($this->validator->getErrors(), true));
                // Erreurs de validation
                $this->session->set('old_data', ['login' => $loginData['login']]);
                header('Location: /');
                exit();
            }
        }
        
        // Affichage du formulaire de connexion
        $this->renderIndex('connexion/connexion');
    }

    public function logout(){
        $this->session->destroy('user');
        header('Location: /');
        exit();
    }

    public function voirplus() {
        $transactionService = App::getDependency('TransactionService');
        $transactions = $transactionService->getAllTransactions();
        $this->communLayout = 'baseLayout';
        $this->renderIndex('voirplus/voirplus', [
            'transactions' => $transactions
        ]);
    }
    
    public function createaccount() {
        $this->communLayout = 'baseLayout';
        $this->renderIndex('createaccount/createaccount');
    }

    public function accessaccount() {
        $this->communLayout = 'baseLayout';
        $user = $this->session->get('user');
        $comptesSecondaires = [];
        if ($user) {
            $comptesSecondaires = App::getDependency('CompteRepository')->findComptesSecondairesByUserId($user['id']);
        }
        $this->renderIndex('accessaccount/accessaccount', [
            'comptesSecondaires' => $comptesSecondaires
        ]);
    }

    public function inscription() {
        $this->renderIndex('inscription/inscription');
    }

    public function verifyCni() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit();
        }

        try {
            error_log("=== DÉBUT VÉRIFICATION CNI ===");
            $rawInput = file_get_contents('php://input');
            error_log("Raw input: " . $rawInput);
            
            $input = json_decode($rawInput, true);
            error_log("Decoded input: " . print_r($input, true));
            
            if (!$input || !isset($input['cni'], $input['login'], $input['adresse'])) {
                error_log("Données manquantes");
                echo json_encode(['success' => false, 'message' => 'Données manquantes']);
                exit();
            }

            $cni = trim($input['cni']);
            $login = trim($input['login']);
            $adresse = trim($input['adresse']);
            
            error_log("CNI: '$cni', Login: '$login', Adresse: '$adresse'");

            // Validation des données CNI (accepte 12 ou 13 chiffres)
            if (!preg_match('/^\d{12,13}$/', $cni)) {
                error_log("Format CNI invalide: '$cni'");
                echo json_encode(['success' => false, 'message' => 'Format CNI invalide (attendu: 12 ou 13 chiffres)']);
                exit();
            }

            if (!preg_match('/^(77|78|70|76|75)\d{7}$/', $login)) {
                error_log("Format téléphone invalide: '$login' (longueur: " . strlen($login) . ")");
                echo json_encode(['success' => false, 'message' => 'Format téléphone invalide (9 chiffres requis: 77xxxxxxx)']);
                exit();
            }

            // Vérifier si le login existe déjà
            error_log("Vérification existence du login...");
            $userRepo = App::getDependency('UserRepository');
            $existing = $userRepo->findByLogin($login);
            if ($existing) {
                error_log("Login déjà utilisé: '$login'");
                echo json_encode(['success' => false, 'message' => 'Ce numéro de téléphone est déjà utilisé']);
                exit();
            }
            error_log("Login disponible");

            // Appel à l'API CNI
            error_log("Appel API CNI...");
            $cniApiService = App::getDependency('CniApiService');
            
            // Mode simulation désactivé en production
            // $cniApiService->enableMockMode();
            
            $cniData = $cniApiService->verifyCni($cni);
            error_log("Résultat API CNI: " . print_r($cniData, true));

            if (!$cniData) {
                echo json_encode(['success' => false, 'message' => 'Le numéro de CNI n\'existe pas']);
                exit();
            }

            // Stocker les données en session
            $this->session->set('cni_data', $cniData);
            $this->session->set('old_data', [
                'login' => $login,
                'adresse' => $adresse
            ]);

            echo json_encode(['success' => true, 'message' => 'CNI vérifiée avec succès']);
            exit();

        } catch (\Exception $e) {
            error_log("Erreur lors de la vérification CNI: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Une erreur est survenue lors de la vérification']);
            exit();
        }
    }

    public function clearCniSession() {
        $this->session->unset('cni_data');
        $this->session->unset('old_data');
        echo json_encode(['success' => true]);
        exit();
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Nettoyer les erreurs précédentes
            $this->session->unset('errors');
            $this->session->unset('success');

            // Validation simple des mots de passe
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';

            if (empty($password) || strlen($password) < 6) {
                $this->validator->addError('password', 'Le mot de passe doit contenir au moins 6 caractères');
                header('Location: /inscription');
                exit();
            }

            if ($password !== $passwordConfirm) {
                $this->validator->addError('password_confirm', 'Les mots de passe ne correspondent pas');
                header('Location: /inscription');
                exit();
            }

            try {
                // Upload de la photo de profil si présente
                $photoProfil = !empty($_FILES['photo']['tmp_name']) ? FileUpload::upload($_FILES['photo'], 'profile') : null;

                $userData = [
                    'nom' => $_POST['nom'],
                    'prenom' => $_POST['prenom'],
                    'login' => $_POST['login'],
                    'password' => $password,
                    'numerocarteidentite' => $_POST['cni'],
                    'adresse' => $_POST['adresse'],
                    'photorecto' => $_POST['cni_recto_url'] ?? null,
                    'photoverso' => $_POST['cni_verso_url'] ?? null,
                    'photo' => $photoProfil,
                    'typeuser' => 'client'
                ];

                // Vérifier à nouveau si le login existe
                $userRepo = App::getDependency('UserRepository');
                $existing = $userRepo->findByLogin($userData['login']);
                if ($existing) {
                    $this->validator->addError('general', "Ce numéro de téléphone est déjà utilisé.");
                    header('Location: /inscription');
                    exit();
                }

                // Enregistrer l'utilisateur
                if ($this->securityService->register($userData)) {
                    // Nettoyer les données de session
                    $this->session->unset('cni_data');
                    $this->session->unset('old_data');
                    
                    $this->validator->setSuccess("Inscription réussie ! Vous pouvez maintenant vous connecter.");
                    header('Location: /');
                    exit();
                } else {
                    $this->validator->addError('general', "Erreur lors de l'inscription.");
                    header('Location: /inscription');
                    exit();
                }
            } catch (\Exception $e) {
                error_log("Erreur lors de l'inscription: " . $e->getMessage());
                $this->validator->addError('general', "Erreur lors de l'inscription : " . $e->getMessage());
                header('Location: /inscription');
                exit();
            }
        }
        
        $this->renderIndex('inscription/inscription');
    }
}
