<?php
namespace App\Controller;

use App\Service\CompteService;
use App\Service\TransactionService;
use App\Core\Abstract\AbstractController;
use App\Core\Session;
use App\Core\App;

class CompteController extends AbstractController
{
    public Session $session;
    private CompteService $compteService;
    private TransactionService $transactionService;

    public function __construct(){
        $this->compteService = App::getDependency('CompteService');
        $this->transactionService = App::getDependency('TransactionService');
        $this->session = App::getDependency('Session');
    }

    public function index(){
        $userId = $this->session->get('user')['id'];


        if(!$userId){

        header('Location:/');
        }

        $compte = $this->compteService->getSolde($userId);


        if ($compte) {

            $this->session->set('compte', $compte);

            // Récupérer les 10 dernières transactions
            $transactions = $this->transactionService->getLast10TransactionsByUserId($userId);
            $this->session->set('transactions', $transactions);

            $this->renderIndex('comptes/accueil');
        }

    }

    public function show(){}
    public function create(){}
    public function store(){}
    public function edit(){}
    public function update(){}
    public function delete(){}



}
