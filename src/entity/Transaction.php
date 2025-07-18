<?php

namespace App\Entity;

class Transaction {
    private int $id;
    private DATETIME $date;
    private string $typeransaction;
    private float $montant;


    public function __construct( $date, $typetransaction, $montant) {
        $this->date = $date;
        $this->typetransaction = $typetransaction;
        $this->montant = $montant;
    }
    public function getId() {
        return $this->id;
    }
    public function getDate() {
        return $this->date;
    }
    public function setDate($date) {
        $this->date = $date;
    }
    public function getTypetransaction() {
        return $this->typetransaction;
    }
    public function setTypetransaction($typetransaction) {
        $this->typetransaction = $typetransaction;
    }
    public function getMontant() {
        return $this->montant;
    }
    public function setMontant() {
        return $this->montant;
    }
}