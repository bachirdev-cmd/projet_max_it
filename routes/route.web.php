<?php

use App\Controller\SecurityController;
use App\Controller\CompteController;
use App\Controller\ErreurController;
return $routes=[
    '/'=>[
        'controller' => SecurityController::class,
        'action' => 'index'
    ],

       '/login'=>[
        'controller' => SecurityController::class,
        'action' => 'index'
    
    ],
    '/authentification'=>[
        'controller' => SecurityController::class,
        'action' => 'login'
    ],
    '/accueil'=>[
        'controller' => CompteController::class,
        'action' => 'index',
        'middleware' => 'auth'
    ],
    '/erreur'=>[
        'controller' => ErreurController::Class,
        'action' => 'erreur'

    ],
    '/logout'=>[
        'controller' => SecurityController::Class,
        'action' => 'logout'
    ],
    '/voirplus'=>[
        'controller' => SecurityController::Class,
        'action' => 'voirplus' 
    ],
    '/createaccount'=> [
        'controller' => SecurityController::class,
        'action' => 'createaccount'
    ],
    '/ajout'=> [
        'controller' => CompteController::class,
        'action' => 'storeSecondaire'
    ],
    '/accessaccount' => [
        'controller' => SecurityController::class,
        'action' => 'accessaccount'
    ],
    

];