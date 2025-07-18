<?php
namespace App\Core;

use App\Core\Session;

class Validator
{
    private static array $errors = [];
    private static ?Validator $instance = null;

    public function __construct()
    {
    }

    public static function getInstance(): Validator
    {
        if (self::$instance === null) {
            self::$instance = new Validator();
        }
        self::$errors = [];
        return self::$instance;
    }

    public function validateLogin(array $data): bool
    {
        $login = trim($data['login'] ?? '');
        $password = trim($data['password'] ?? '');

        if (empty($login)) {
            self::$errors['login'] = 'Le login est obligatoire';
        }elseif (strlen($login) < 3) {
            self::$errors['login'] = 'Le login doit contenir au moins 3 caractères';
        } 

        if (empty($password)) {
            self::$errors['password'] = 'Le mot de passe est obligatoire';
        } elseif (strlen($password) < 4) {
            self::$errors['password'] = 'Le mot de passe doit contenir au moins 4 caractères';
        }

        if (!empty(self::$errors)) {
            Session::getInstance()->set('errors', self::$errors);
            return false;
        }

        return true;
    }

    public function addError(string $field, string $message): void
    {
        self::$errors[$field] = $message;
        Session::getInstance()->set('errors', self::$errors);
    }

    public function getErrors(): array
    {
        return self::$errors;
    }
    public function setSuccess(string $message)
    {
        Session::getInstance()->set('success', $message);
    }

}