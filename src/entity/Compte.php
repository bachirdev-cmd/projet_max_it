<?php
namespace App\Entity;

class Compte{
    private int $id;
    private float $solde;
    private DateTime $dateCreation;
    private string $numerotel;
    private CompteEnum $typeCompte;

    public function __construct($id, $solde, $dateCreation, $numerotel, $typeCompte) {
        $this->id = $id;
        $this->solde = $solde;
        $this->dateCreation = $dateCreation;
        $this->numerotel = $numerotel;
        $this->typeCompte = $typeCompte;
    }
    
    public function getId() {
        return $this->id;
    }
    public function getSolde() {
        return $this->solde;
    }
    public function getDateCreation() {
        return $this->dateCreation;
    }
    public function getNumerotel() {
        return $this->numerotel;
    }
    public function getTypeCompte() {
        return $this->typeCompte;
    }
    public function setId($id) {
        $this->id = $id;
    }
    public function setSolde($solde) {
        $this->solde = $solde;
    }
    public function setDateCreation($dateCreation) {
        $this->dateCreation = $dateCreation;
    }
    public function setNumerotel($numerotel) {
        $this->numerotel = $numerotel;
    }
    public function setTypeCompte($typeCompte) {
        $this->typeCompte = $typeCompte;
    }


    
}