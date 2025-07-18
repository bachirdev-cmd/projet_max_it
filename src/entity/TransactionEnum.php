<?php
namespace App\Entity;

enum TransactionEnum: string
{
    case DEPOT = 'depot';
    case RETRAIT = 'retrait';
    case PAIEMENT = 'paiement';
}
