<?php
namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Service\SecurityService;
use App\Core\Session;
use App\Core\Validator;
use App\Core\App;


class SecurityController extends AbstractController
{
    private SecurityService $securityService;
    private Validator $valisator;
    protected Session $session; // <-- ici

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


    public function login(){
           $loginData = [
            'login' => $_POST['login'] ?? '',
            'password' => $_POST['password'] ?? ''
        ];

        if ($this->validator->validateLogin($loginData)) {
            $user = $this->securityService->login($loginData['login'], $loginData['password']);
            if ($user) {
                $this->session->set('user', $user);
                header('Location: /accueil');
                exit();
            } else {

                $this->validator->addError('login', "le login est incorrecte");
                header('Location: /login');
                exit();
            }
        } else {
            header('Location: /');
            exit();
        } 
    }
    public function logout(){
          // Supprime la session utilisateur
    $this->session->destroy('user');

    // Redirige vers la page de connexion
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
}

