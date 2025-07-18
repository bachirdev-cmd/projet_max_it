<?php

namespace App\Controller;

use App\Service\TransactionService;
use App\Core\Session;
use App\Core\Abstract\AbstractController;
use App\Core\App;

class TransactionController extends AbstractController
{
    private TransactionService $transactionService;
    private Session $session;

    public function __construct()
    {
        $this->transactionService = App::getDependency('TransactionService');
        $this->session = App::getDependency('Session');
    }

    /**
     * Affiche les 10 dernières transactions du user connecté
     */
    public function index()
    {
        $user = $this->session->get('user');
        $userId = $user['id'];
        $transactions = $this->transactionService->getLast10TransactionsByUserId($userId);
        $this->render('Transaction/historique10derniere', [
            'transactions' => $transactions
        ]);
    }

    public function afficheTOusLesTransactions()
    {
        $transactions = $this->transactionService->getAllTransactions();

        $this->renderIndex('voirplus/voirplus', [
            'transactions' => $transactions
        ]);
    }

    // Fonctions de base (à implémenter plus tard si besoin)
    public function show() {
         // Logique pour afficher une transaction spécifique
    }
    public function create() {
     // Logique pour créer une transaction
    }
    public function store() {
     // Logique pour stocker une nouvelle transaction
    }
    public function edit() {
     // Logique pour éditer une transaction
    }
    public function update() {
     // Logique pour mettre à jour une transaction
    }
    public function delete() {}
}
    
