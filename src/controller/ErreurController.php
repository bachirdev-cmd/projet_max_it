<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;

class ErreurController extends AbstractController{

    public function erreur(){
     $this->renderIndex('erreur/r404');
     }

       public function index(){}
    public function show(){}
    public function create(){}
    public function store(){}
    public function edit(){}
    public function update(){}
    public function delete(){}
}