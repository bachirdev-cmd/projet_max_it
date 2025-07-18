<?php
namespace App\Service;

use App\Repository\TransactionRepository;
use App\Core\App;


class TransactionService{
    private TransactionRepository $transactionRepository;
    private static ?TransactionService $instance = null;

    public function __construct(){
        $this->transactionRepository = App::getDependency('TransactionRepository');
    }
    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    } 
    public function getTransactionsByCompteId(int $compteId): ?array {
        return $this->transactionRepository->getTransactionsByCompteId($compteId);
    }
    public function getLast10TransactionsByUserId(int $userId): ?array {
        return $this->transactionRepository->getLast10Transactions($userId);
    }
    public function getAllTransactions(): ?array {
        return $this->transactionRepository->getAllTransactions();
    }
}



